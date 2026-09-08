from datetime import datetime
import time
import uuid
import hmac
import hashlib
import base64
import json
import requests
from urllib.parse import quote
from django.conf import settings
from django.core.exceptions import ImproperlyConfigured

class BiteshipService:
    def __init__(self):
        self.api_key = settings.BITESHIP_API_KEY.strip()
        if not self.api_key:
            raise ImproperlyConfigured(
                "BITESHIP_API_KEY belum diatur. Isi dengan API key production Biteship."
            )
        if not self.api_key.startswith("biteship_live."):
            raise ImproperlyConfigured(
                "BITESHIP_API_KEY harus berupa production key dengan awalan biteship_live."
            )
        self.base_url = "https://api.biteship.com/v1"

    @property
    def headers(self):
        return {
            "Authorization": self.api_key,
            "Content-Type": "application/json",
        }

    def search_areas(self, query):
        response = requests.get(
            f"{self.base_url}/maps/areas",
            params={"countries": "ID", "input": query, "type": "single"},
            headers=self.headers,
            timeout=15,
        )
        result = response.json() if response.content else {}
        if not response.ok:
            raise RuntimeError(
                result.get("error") or result.get("message") or "Pencarian alamat gagal."
            )
        return result.get("areas", [])

    def get_rates(self, destination_area_id, items, couriers="jne"):
        payload = {
            "origin_postal_code": 40252,
            "destination_area_id": destination_area_id,
            "couriers": couriers,
            "items": items,
        }
        response = requests.post(
            f"{self.base_url}/rates/couriers",
            json=payload,
            headers=self.headers,
            timeout=20,
        )
        try:
            result = response.json()
        except ValueError:
            result = {}
        if not response.ok:
            raise RuntimeError(
                result.get("error")
                or result.get("message")
                or response.text
                or "Tarif pengiriman tidak tersedia."
            )
        return result.get("pricing", [])

    def retrieve_tracking(self, tracking_id=None, waybill_id=None, courier_code=None):
        if tracking_id:
            path = f"/trackings/{quote(str(tracking_id), safe='')}"
        elif waybill_id and courier_code:
            path = (
                f"/trackings/{quote(str(waybill_id), safe='')}"
                f"/couriers/{quote(str(courier_code).lower(), safe='')}"
            )
        else:
            raise ValueError("Tracking ID atau kombinasi resi dan kurir wajib tersedia.")

        response = requests.get(
            f"{self.base_url}{path}",
            headers=self.headers,
            timeout=20,
        )
        try:
            result = response.json()
        except ValueError:
            result = {}
        if not response.ok or not result.get("success"):
            raise RuntimeError(
                result.get("error")
                or result.get("message")
                or response.text
                or "Pelacakan Biteship tidak tersedia."
            )
        return result

    def create_order(self, pesanan):
        url = f"{self.base_url}/orders"
        headers = self.headers
        
        # Payload Minimalis yang disukai API Biteship
        payload = {
            "shipper_contact_name": "Galang Raka",
            "shipper_contact_phone": "0895428171038",
            "origin_contact_name": "Galang Raka",
            "origin_contact_phone": "0895428171038",
            "origin_address": "Jl. Otto Iskandar Dinata No.392, Nyengseret, Kec. Astanaanyar, Kota Bandung",
            "origin_postal_code": 40252,
            "destination_contact_name": pesanan.nama_penerima,
            "destination_contact_phone": pesanan.telepon,
            "destination_address": pesanan.alamat_lengkap,
            "destination_postal_code": int(pesanan.kode_pos),
            "destination_area_id": pesanan.destination_area_id,
            
            # Ganti ke "now" agar tidak butuh input tanggal
            "delivery_type": "now", 
            "courier_company": "jne",
            "courier_type": pesanan.kurir_layanan or "reg",
            "reference_id": f"commerce-{pesanan.id}",
            
            "items": [
                {
                    "name": detail.produk.nama if detail.produk else "Produk",
                    "description": "Produk dari toko online",
                    "value": int(detail.harga_saat_beli),
                    "length": detail.produk.panjang_cm if detail.produk else 10,
                    "width": detail.produk.lebar_cm if detail.produk else 10,
                    "height": detail.produk.tinggi_cm if detail.produk else 10,
                    "weight": detail.produk.berat_gram if detail.produk else 1000,
                    "quantity": detail.jumlah,
                }
                for detail in pesanan.items.select_related("produk").all()
            ],
        }
        
        response = requests.post(url, json=payload, headers=headers, timeout=30)
        try:
            result = response.json()
        except ValueError:
            result = {}

        if not response.ok:
            message = (
                result.get("error")
                or result.get("message")
                or response.text
                or f"HTTP {response.status_code}"
            )
            raise RuntimeError(f"Biteship menolak pembuatan order: {message}")

        return result
    
class DokuService:
    def __init__(self):
        self.client_id = settings.DOKU_CLIENT_ID.strip()
        self.secret_key = settings.DOKU_SECRET_KEY.strip()
        if not self.client_id or not self.secret_key:
            raise ImproperlyConfigured(
                "DOKU_CLIENT_ID dan DOKU_SECRET_KEY production belum diatur."
            )

        self.base_url = "https://api.doku.com/checkout/v1/payment"

    def generate_secret(self):
        # Pastikan secret key dikonversi dengan benar ke bytes
        return self.secret_key.encode('utf-8')

    def generate_signature(self, target_path, request_id, timestamp, request_body):
        digest = base64.b64encode(
            hashlib.sha256(request_body.encode()).digest()
        ).decode()

        string_to_sign = (
            f"Client-Id:{self.client_id}\n"
            f"Request-Id:{request_id}\n"
            f"Request-Timestamp:{timestamp}\n"
            f"Request-Target:{target_path}\n"
            f"Digest:{digest}"
        )

        signature = base64.b64encode(
            hmac.new(
                self.secret_key.encode(),
                string_to_sign.encode(),
                hashlib.sha256
            ).digest()
        ).decode()

        return f"HMACSHA256={signature}", digest

    def verify_notification(self, headers, request_body, target_path):
        client_id = headers.get('Client-Id', '')
        request_id = headers.get('Request-Id', '')
        timestamp = headers.get('Request-Timestamp', '')
        received_signature = headers.get('Signature', '')

        if client_id != self.client_id:
            return False
        if not request_id or not timestamp or not received_signature:
            return False

        expected_signature, _ = self.generate_signature(
            target_path,
            request_id,
            timestamp,
            request_body,
        )
        return hmac.compare_digest(received_signature, expected_signature)

    def create_checkout_url(self, pesanan):

        target_path = "/checkout/v1/payment"
        url = self.base_url

        timestamp = time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime())
        request_id = str(uuid.uuid4())
        
        invoice_number = f"INV-{pesanan.id}-{int(time.time())}"
        payload = {
            "order": {
                "amount": int(pesanan.total_harga),
                "invoice_number": invoice_number,
                "currency": "IDR",
                "callback_url": (
                    f"{settings.PUBLIC_BASE_URL}/commerce/{pesanan.user.username}/"
                ),
                "expiry_time": 60
            },
            "payment": {
                "payment_due_date": 60
            },
            "customer": {
                "name": pesanan.nama_penerima or pesanan.user.username,
                "email": pesanan.user.email or "customer@example.com"
            },
            "additional_info": {
                "override_notification_url": (
                    f"{settings.PUBLIC_BASE_URL}/api/doku/notification/"
                )
            }
        }

        # Body JSON tanpa spasi
        request_body = json.dumps(payload, separators=(',', ':'))

        # Generate Signature dan Digest
        signature, digest = self.generate_signature(
            target_path,
            request_id,
            timestamp,
            request_body
        )

        headers = {
            "Client-Id": self.client_id,
            "Request-Id": request_id,
            "Request-Timestamp": timestamp,
            "Signature": signature,
            "Content-Type": "application/json",
            "Digest": digest
        }

        response = requests.post(
            url,
            data=request_body,
            headers=headers,
            timeout=30
        )

        if response.status_code in [200, 201]:
            res_json = response.json()

            pesanan.doku_invoice_number = invoice_number
            pesanan.doku_payment_url = (
                res_json
                .get("response", {})
                .get("payment", {})
                .get("url")
            )
            
            pesanan.save()

            return {
                "success": True,
                "payment_url": pesanan.doku_payment_url,
                "invoice_number": invoice_number,
                "resi": None
            }

        return {
            "success": False,
            "message": response.text
        }
