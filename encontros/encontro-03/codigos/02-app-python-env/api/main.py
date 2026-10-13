import os
import urllib.request
from flask import Flask, jsonify

app = Flask(__name__)

# Captura variáveis de ambiente injetadas pelo Docker Compose
ENVIRONMENT = os.getenv("APP_ENV", "producao")
SECRET_KEY = os.getenv("SECRET_KEY", "padrao")
WORKER_URL = os.getenv("WORKER_URL", "http://worker-interno:8000")

@app.route('/')
def home():
    return jsonify({
        "mensagem": "API Python (Custom Build) operacional!",
        "ambiente": ENVIRONMENT,
        "chave_secreta_carregada": bool(SECRET_KEY and SECRET_KEY != "padrao"),
        "dica": "Acesse /status-worker para testar a comunicação interna via expose"
    })

@app.route('/status-worker')
def status_worker():
    try:
        # A API tenta se comunicar com o Worker via rede interna do Docker (usando o nome do serviço)
        req = urllib.request.urlopen(WORKER_URL, timeout=3)
        resposta = req.read().decode('utf-8')
        return jsonify({
            "status": "sucesso",
            "resposta_worker": resposta,
            "explicacao": "A API (com porta pública 5000) acessou o Worker (porta 8000 restrita por expose) na rede interna!"
        })
    except Exception as e:
        return jsonify({
            "status": "erro",
            "mensagem": f"Não foi possível conectar ao worker interno: {str(e)}"
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

