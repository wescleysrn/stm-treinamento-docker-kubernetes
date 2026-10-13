import os
from flask import Flask, jsonify
import psycopg2

app = Flask(__name__)

# Configurações obtidas do ambiente
DB_HOST = os.getenv("DB_HOST", "db-postgres")
DB_NAME = os.getenv("POSTGRES_DB", "treinamento_db")
DB_USER = os.getenv("POSTGRES_USER", "appuser")
DB_PASS = os.getenv("POSTGRES_PASSWORD", "segredo_postgres_2026")

def get_db_connection():
    return psycopg2.connect(
        host=DB_HOST,
        database=DB_NAME,
        user=DB_USER,
        password=DB_PASS
    )

@app.route('/')
def index():
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        
        # Cria a tabela caso não exista
        cur.execute("CREATE TABLE IF NOT EXISTS acessos (id SERIAL PRIMARY KEY, data TIMESTAMP DEFAULT CURRENT_TIMESTAMP);")
        cur.execute("INSERT INTO acessos DEFAULT VALUES RETURNING id, data;")
        ultimo_acesso = cur.fetchone()
        conn.commit()
        
        cur.execute("SELECT COUNT(*) FROM acessos;")
        total_acessos = cur.fetchone()[0]
        
        cur.close()
        conn.close()

        return jsonify({
            "status": "Conectado ao PostgreSQL com sucesso!",
            "novo_registro_id": ultimo_acesso[0],
            "data_registro": str(ultimo_acesso[1]),
            "total_registros_persistidos": total_acessos,
            "mensagem": "Recarregue a página para ver o contador aumentar."
        })
    except Exception as e:
        return jsonify({
            "status": "Erro ao conectar no PostgreSQL",
            "detalhe": str(e)
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
