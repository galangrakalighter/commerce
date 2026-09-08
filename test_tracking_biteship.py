import json
import os
import sys

import requests


tracking_id = sys.argv[1] if len(sys.argv) > 1 else ""
api_key = os.environ.get("BITESHIP_API_KEY", "").strip()

if not api_key or not tracking_id:
    raise SystemExit(
        "Gunakan: python test_tracking_biteship.py TRACKING_ID_BITESHIP "
        "(BITESHIP_API_KEY harus tersedia di environment)."
    )

response = requests.get(
    f"https://api.biteship.com/v1/trackings/{tracking_id}",
    headers={"Authorization": api_key, "Content-Type": "application/json"},
    timeout=20,
)
print(json.dumps(response.json(), indent=2))
response.raise_for_status()
