import requests
from datetime import datetime

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