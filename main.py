from flask import Flask, request, Response, jsonify, send_file
from twilio.rest import Client
import os

app = Flask(__name__)



ACCOUNT_SID = os.getenv("TWILIO_ACCOUNT_SID")
AUTH_TOKEN = os.getenv("TWILIO_AUTH_TOKEN")
TWILIO_NUMBER = os.getenv("TWILIO_PHONE_NUMBER")
BASE_URL = "https://unclinging-donn-oceanographically.ngrok-free.dev"

client = Client(ACCOUNT_SID, AUTH_TOKEN)

MP3_FILENAME = "swahili.mp3"


@app.route("/", methods=["GET"])
def home():
    return "App is running."


@app.route("/audio", methods=["GET"])
def audio():
    file_path = os.path.join(os.path.dirname(__file__), MP3_FILENAME)
    print("AUDIO PATH:", file_path)

    if not os.path.exists(file_path):
        return "Audio file not found.", 404

    return send_file(
        file_path,
        mimetype="audio/mpeg",
        as_attachment=False,
        download_name="swahili.mp3",
    )


@app.route("/voice", methods=["GET", "POST"])
def voice():
    twiml = f"""<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Play>{BASE_URL}/audio</Play>
</Response>"""
    return Response(twiml, mimetype="text/xml")


@app.route("/call", methods=["POST"])
def call():
    to_number = request.form.get("to")

    if not to_number:
        return jsonify({"error": "Missing 'to' phone number"}), 400

    try:
        call = client.calls.create(
            to=to_number,
            from_=TWILIO_NUMBER,
            url=f"{BASE_URL}/voice"
        )
        return jsonify({
            "status": "started",
            "call_sid": call.sid,
            "to": to_number
        })
    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500


if __name__ == "__main__":
    app.run(port=5000, debug=True)
