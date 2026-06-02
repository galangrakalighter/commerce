import requests
import json

# 1. Masukkan API Key BinderByte milikmu di sini
API_KEY = "062af482976fa00ed00067f4570424d53742c507a2341932dfe8b00dc1f89bbd"

# 2. Untuk testing, gunakan kurir dan nomor resi asli yang valid.
# (Kamu bisa pakai nomor resi dari paket belanjaan pribadimu yang sudah selesai/sedang jalan)
KURIER = "spx" 
NOMOR_RESI = "260506P9RF9FN0" # <-- Ganti dengan nomor resi asli

url = "https://api.binderbyte.com/v1/track"
params = {
    'api_key': API_KEY,
    'courier': KURIER,
    'awb': NOMOR_RESI,
    'number': 84281
}

print("Sedang menyambungkan ke API BinderByte...")

try:
    response = requests.get(url, params=params)
    data = response.json()
    
    # Cetak hasil agar rapi dan mudah dibaca
    print("\n--- HASIL RESPONS API ---")
    print(json.dumps(data, indent=4))

except Exception as e:
    print(f"Terjadi error: {e}")