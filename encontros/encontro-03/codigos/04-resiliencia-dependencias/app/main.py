import os
import time
from flask import Flask, jsonify
import psycopg2
import redis

app = Flask(__name__)

# Configurações de ambiente
DB_HOST = os.getenv("DB_HOST", "db")
DB_USER = os.getenv("DB_USER", "admin")
DB_PASS = os.getenv("DB_PASS", "secretpassword")
DB_NAME = os.getenv("DB_NAME", "app_db")
REDIS_HOST = os.getenv("REDIS_HOST", "cache")

@app.route('/')
def index():
    # 1. Teste do Redis (Cache)
    try:
        r = redis.Redis(host=REDIS_HOST, port=6379, socket_connect_timeout=2)
        visitas = r.incr("contador_visitas")
    except Exception as e:
        visitas = f"Erro no Redis: {str(e)}"

    # 2. Teste do PostgreSQL (Banco de Dados)
    try:
        conn = psycopg2.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASS,
            dbname=DB_NAME,
            connect_timeout=3
        )
        cur = conn.cursor()
        cur.execute("CREATE TABLE IF NOT EXISTS logs (id SERIAL PRIMARY KEY, timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP);")
        cur.execute("INSERT INTO logs DEFAULT VALUES RETURNING id;")
        log_id = cur.fetchone()[0]
        conn.commit()
        cur.close()
        conn.close()
        db_status = f"Sucesso! Registro #{log_id} gravado no banco."
    except Exception as e:
        db_status = f"Erro no PostgreSQL: {str(e)}"

    return jsonify({
        "status_aplicacao": "Online",
        "redis_visitas": visitas,
        "postgres_status": db_status,
        "mensagem": "A API só iniciou após o Healthcheck do Banco e do Redis confirmarem status HEALTHY!"
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
