to test main

Run:
 python main.py

in terminal:
ngrok http 5000

To call:
Invoke-WebRequest -Uri http://127.0.0.1:5000/call -Method POST -Body @{to="+32489986553"} -UseBasicParsing
