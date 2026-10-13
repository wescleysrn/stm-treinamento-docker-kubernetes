import os
import time
import redis

REDIS_HOST = os.getenv("REDIS_HOST", "cache")
ENV_NAME = os.getenv("APP_ENV", "producao")

print(f"🚀 [Worker] Serviço de processamento ativo no ambiente: {ENV_NAME}")

while True:
    try:
        r = redis.Redis(host=REDIS_HOST, port=6379, socket_connect_timeout=2)
        # Remove e pega o primeiro item da fila no Redis
        task = r.lpop("task_queue")
        if task:
            print(f"⚡ [Worker - {ENV_NAME}] Processando tarefa consumida da fila: {task.decode('utf-8')}")
        time.sleep(2)
    except Exception as e:
        print(f"⚠️ [Worker] Aguardando conexão com Redis: {e}")
        time.sleep(4)
