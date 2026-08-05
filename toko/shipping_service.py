from datetime import datetime
import time
import uuid
import hmac
import hashlib
import base64
import json
import requests

class BiteshipService:
    def __init__(self):
        # Gunakan API Key Production jika sudah siap, atau tetap gunakan Test key
        self.api_key = "biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiY29tbWVyY2UiLCJ1c2VySWQiOiI2YTMxZmY5NTgwOWQ2YTA5ZWNjZjBiMDciLCJpYXQiOjE3ODE2NjE4MDJ9.UDlUMmUzLfLCztTE6grJ7dleZ2jlKrbo7rV9l2PaFaw" 
        self.base_url = "https://api.biteship.com/v1"

    def create_order(self, pesanan):
        url = f"{self.base_url}/orders"
        headers = {
            "Authorization": f"Bearer {self.api_key}",
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
        
        response = requests.post(url, json=payload, headers=headers)
        return response.json()
    
class DokuService:
    def __init__(self):
        # Masukkan Client ID dan Secret Key Anda secara langsung di sini untuk testing
        self.client_id = "BRN-0238-1785732242754"  # Ganti dengan Client ID Anda dari DOKU
        self.secret_key = "SK-G90cnCsKXJACD5LDuhU4"   # Ganti dengan Secret Key Anda dari DOKU
        
        # Karena kita pakai sandbox, pastikan URL-nya sandbox
        self.is_sandbox = True
        
        if self.is_sandbox:
            self.base_url = "https://api-sandbox.doku.com/checkout/v1/payment"
        else:
            self.base_url = "https://api.doku.com"

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

        print(string_to_sign)

        signature = base64.b64encode(
            hmac.new(
                self.secret_key.encode(),
                string_to_sign.encode(),
                hashlib.sha256
            ).digest()
        ).decode()

        return f"HMACSHA256={signature}", digest

    def create_checkout_url(self, pesanan):

        target_path = "/checkout/v1/payment"
        url = self.base_url

        timestamp = time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime())
        request_id = str(uuid.uuid4())
        
        print(type(pesanan.total_harga))
        print(pesanan.total_harga)

        payload = {
            "order": {
                "amount": int(pesanan.total_harga),
                "invoice_number": f"INV-{pesanan.id}-{int(time.time())}",
                "currency": "IDR",
                "callback_url": f"https://paislee-frigid-sherrie.ngrok-free.dev/{pesanan.user.username}/",
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

        print("\n========== REQUEST ==========")
        print("URL :", url)
        print("HEADERS :", json.dumps(headers, indent=4))
        print("BODY :", request_body)
        print("=============================\n")

        response = requests.post(
            url,
            data=request_body,
            headers=headers
        )

        print("STATUS :", response.status_code)
        print("RESPONSE :", response.text)
        
        invoice_number = f"INV-{pesanan.id}-{int(time.time())}"

        if response.status_code in [200, 201]:
            res_json = response.json()

            pesanan.doku_invoice_number = invoice_number
            pesanan.doku_payment_url = (
                res_json
                .get("response", {})
                .get("payment", {})
                .get("url")
            )
            
            biteship = BiteshipService()

            shipping_result = biteship.create_order(pesanan)


            if shipping_result.get("success"):

                tracking_id = (
                    shipping_result
                    .get("courier", {})
                    .get("tracking_id")
                )


                pesanan.no_resi = tracking_id
                pesanan.status = "KIRIM"

            else:

                # pembayaran berhasil tapi pengiriman gagal
                pesanan.status = "MENUNGGU"


            pesanan.save()

            return {
                "success": True,
                "payment_url": pesanan.doku_payment_url,
                "invoice_number": invoice_number,
                "resi": pesanan.no_resi
            }

        return {
            "success": False,
            "message": response.text
        }
        
        