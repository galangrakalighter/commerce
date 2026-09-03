from datetime import datetime
import time
import uuid
import hmac
import hashlib
import base64
import json
import requests
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

    def create_order(self, pesanan):
        url = f"{self.base_url}/orders"
        headers = {
            "Authorization": self.api_key,
            "Content-Type": "application/json"
        }
        
        # Payload Minimalis yang disukai API Biteship
        payload = {
            "shipper_contact_name": "Galang Raka",
            "shipper_contact_phone": "0895428171038",
            "origin_contact_name": "Galang Raka",
            "origin_contact_phone": "0895428171038",
            "origin_address": "Jl. Otto Iskandar Dinata No.392, Nyengseret, Kec. Astanaanyar, Kota Bandung",
            "origin_postal_code": 40252,
            "origin_coordinate": {
                "latitude": -6.89113689999999,
                "longitude": 107.5621076
            },
            "destination_contact_name": pesanan.nama_penerima,
            "destination_contact_phone": pesanan.telepon,
            "destination_address": pesanan.alamat_lengkap,
            "destination_postal_code": int(pesanan.kode_pos) if pesanan.kode_pos else 40175,
            
            # Ganti ke "now" agar tidak butuh input tanggal
            "delivery_type": "now", 
            "courier_company": "jne",
            "courier_type": "reg",
            
            "items": [
                {
                    "name": "Produk",
                    "description": "Produk dari toko online",
                    "value": int(pesanan.total_harga),
                    "length": 10,
                    "width": 10,
                    "height": 10,
                    "weight": 1000, # dalam gram
                    "quantity": 1
                }
            ]
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
