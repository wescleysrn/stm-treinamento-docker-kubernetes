import os
from flask import Flask, jsonify
import redis

app = Flask(__name__)

REDIS_HOST = os.getenv("REDIS_HOST", "cache")
ENV_NAME = os.getenv("APP_ENV", "producao")

@app.route('/')
def index():
    try:
        r = redis.Redis(host=REDIS_HOST, port=6379, socket_connect_timeout=2)
        queue_length = r.llen("task_queue")
        return jsonify({
            "servico": "API Microserviços",
            "ambiente_atual": ENV_NAME,
            "flask_debug": app.debug,
            "tarefas_na_fila": queue_length,
            "instrucao": "Acesse POST /add-task para enfileirar um processamento no Worker!"
        })
    except Exception as e:
        return jsonify({"status": "erro", "detalhe": str(e)}), 500

@app.route('/add-task', methods=['GET', 'POST'])
def add_task():
    try:
        r = redis.Redis(host=REDIS_HOST, port=6379, socket_connect_timeout=2)
        total = r.rpush("task_queue", "relatorio_mensal_pdf")
        return jsonify({
            "status": "Sucesso",
            "mensagem": "Tarefa enfileirada no Redis!",
            "total_pendente_na_fila": total
        })
    except Exception as e:
        return jsonify({"status": "erro", "detalhe": str(e)}), 500

if __name__ == '__main__':
    debug_flag = os.getenv("FLASK_DEBUG", "0") == "1"
    app.run(host='0.0.0.0', port=5000, debug=debug_flag)
