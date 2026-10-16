import os
import sys
from flask import Flask

app = Flask(__name__)

# Carregamento das variáveis de ambiente
ENV = os.getenv("APP_ENV", "desconhecido")
WELCOME_MSG = os.getenv("WELCOME_MSG", "Mensagem padrao")
DB_USER = os.getenv("DB_USER", "nao_definido")

# Falha proposital para simulação de bug em runtime
SIMULATE_CRASH = os.getenv("SIMULATE_CRASH", "false")

if SIMULATE_CRASH.lower() == "true":
    print("ERRO CRÍTICO: Falha simulada de inicialização na aplicação!", file=sys.stderr)
    sys.exit(1)

@app.route('/')
def home():
    return f"""
    <h1>Sistema Corporativo - STM</h1>
    <p><b>Ambiente:</b> {ENV}</p>
    <p><b>Mensagem:</b> {WELCOME_MSG}</p>
    <p><b>Usuario BD:</b> {DB_USER}</p>
    """

@app.route('/healthz')
def health():
    return "OK", 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
