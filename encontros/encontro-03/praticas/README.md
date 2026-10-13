# Exemplos Práticos de Docker Compose

| Código do Exemplo | Nome / Arquitetura | Recursos do Compose Demonstrados | Objetivo Didático e Descrição da Prática |
|---|---|---|---|
| `01-web-estatico` | Servidor Web Nginx com HTML Customizado | - `image`<br>- `ports`<br>- `volumes` (bind mount)<br>- `restart: unless-stopped` | **Nível: Iniciante**<br><br>Subir um serviço simples utilizando uma imagem pronta do Docker Hub (`nginx:alpine`). Mapear uma porta do host para o container (`8080:80`) com `ports`, utilizar um bind mount para sobrescrever o `index.html` em tempo real e demonstrar o comportamento de recuperação automática com a política de `restart`. |
| `02-app-python-env` | API Flask com Serviço Interno de Processamento | - `build` vs. `image`<br>- `ports` vs. `expose`<br>- `environment`<br>- Arquivo `.env` | **Nível: Básico**<br><br>Construir uma imagem própria a partir de um Dockerfile local usando `build`, comparando seu funcionamento com o uso direto de `image`. Demonstrar a diferença entre expor portas para o mundo externo (`ports`) e expor portas apenas para a rede interna dos containers (`expose`). Injetar configurações e segredos via arquivo `.env` e bloco `environment`. |
| `03-stack-banco-dados` | Aplicação Web + Banco PostgreSQL | - `networks` (isolamento de redes)<br>- `volumes` (named volumes)<br>- `environment` / `env_file` | **Nível: Intermediário**<br><br>Criar duas redes distintas (`frontend-net` e `backend-net`) para exemplificar o isolamento e a comunicação seletiva entre containers. Explicar a diferença prática de persistência de dados entre bind mounts e named volumes (no diretório do SGBD) e organizar credenciais sensíveis usando a diretiva `env_file`. |
| `04-resiliencia-dependencias` | Stack Web + API + Redis Cache + Banco de Dados | - `healthcheck`<br>- `depends_on` (com `condition`)<br>- `restart: on-failure` com retentativas | **Nível: Avançado**<br><br>Abordar o problema clássico de *race condition* na inicialização de bancos de dados. Criar blocos de `healthcheck` customizados (executando comandos como `pg_isready` ou `redis-cli ping`) e utilizar `depends_on` com `condition: service_healthy` para garantir que a aplicação só suba após o banco estar pronto para aceitar conexões. |
| `05-microservicos-override` | Arquitetura de Microserviços Multi-Ambiente | - `compose.yaml`<br>- `compose.override.yaml`<br>- Consolidação de todos os recursos anteriores | **Nível: Especialista**<br><br>Simular um ambiente profissional de microserviços. Demonstrar como o Docker Compose mescla automaticamente o arquivo base `compose.yaml` com o `compose.override.yaml` para o ambiente de desenvolvimento (por exemplo, montagem de código em tempo real, portas de debug e reload automático), sem impactar a configuração limpa voltada para produção. |

## Progressão Didática

1. **Iniciante:** execução de um servidor web, mapeamento de portas e montagem de arquivos.
2. **Básico:** construção de imagens próprias, configuração de variáveis de ambiente e comunicação entre serviços.
3. **Intermediário:** isolamento de redes, persistência de dados e gerenciamento de configurações.
4. **Avançado:** verificações de saúde, dependências entre serviços e estratégias de recuperação.
5. **Especialista:** composição de configurações para diferentes ambientes e arquitetura de microserviços.

## Estrutura

```text
codigos/
├── 01-web-estatico/
│   ├── compose.yaml
│   └── html/
│       └── index.html
├── 02-app-python-env/
│   ├── .env
│   ├── compose.yaml
│   └── app/
│       ├── Dockerfile
│       └── main.py
├── 03-stack-banco-dados/
│   ├── .env.db
│   └── compose.yaml
├── 04-resiliencia-dependencias/
│   └── compose.yaml
└── 05-microservicos-override/
    ├── compose.yaml
    ├── compose.override.yaml
    ├── api/
    └── worker/
```

### 01-web-estatico

📂 Estrutura de Pastas do Projeto

```text
01-web-estatico/
├── compose.yaml
└── html/
    └── index.html
```

1️⃣ Arquivo: html/index.html

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinamento Docker Compose</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0db7ed;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(0, 0, 0, 0.2);
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 { margin-top: 0; }
        p { font-size: 1.2rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐳 Exemplo 01: Servidor Web Estático</h1>
        <p>Este HTML está sendo servido diretamente pelo Nginx via <strong>Bind Mount</strong>!</p>
        <p><small>Altere este arquivo na sua máquina e recarregue a página no navegador.</small></p>
    </div>
</body>
</html>
```

2️⃣ Arquivo: compose.yaml

```yaml
services:
  web:
    # 1. IMAGE: Define a imagem oficial e leve do Nginx vinda do Docker Hub
    image: nginx:alpine

    # Define um nome amigável para o container
    container_name: web-estatico-app

    # 2. PORTS: Mapeia a porta do Host (maquina do aluno) para a porta interna do Container
    # Sintaxe: "PORTA_HOST:PORTA_CONTAINER"
    ports:
      - "8080:80"

    # 3. VOLUMES: Bind Mount montando a pasta local ./html dentro do diretório padrao do Nginx
    # O sufixo :ro (read-only) garante que o container apenas leia o diretório
    volumes:
      - ./html:/usr/share/nginx/html:ro

    # 4. RESTART: Política de reinicialização do container
    # Reinia o container automaticamente se ele cair ou ao reiniciar o Docker, a menos que seja parado manualmente
    restart: unless-stopped
```

Passo 1: Subir o serviço

```bash
docker compose up -d
```

Passo 2: Testar o mapeamento de portas (ports)

Abrir pelo navegador

```bash
curl http://localhost:8080
```

Passo 3: Demonstrar o Bind Mount (volumes)

Abra o arquivo html/index.html no seu editor, faça uma alteração no texto (ex: mude o título ou a cor do fundo) e salve o arquivo.

Recarregue a página no navegador (F5).

O que destacar: Como usamos o Bind Mount (./html:/usr/share/nginx/html), as alterações na máquina host refletem instantaneamente no container sem necessidade de rebuild ou restart.

Passo 4: Testar a Política de Reinicialização (restart: unless-stopped)

Force uma interrupção inesperada no container matando o processo principal:

```bash
docker kill web-estatico-app
```
Verifique o status com docker ps.

O que destacar: Em poucos segundos o Docker reiniciará o container automaticamente por conta da diretiva restart: unless-stopped.

Passo 5: Limpeza

```bash
docker compose down
```

### 02-app-python-env

📂 Estrutura de Pastas do Projeto

```text
02-app-python-env/
├── .env
├── compose.yaml
├── api/
│   ├── Dockerfile
│   ├── main.py
│   └── requirements.txt
└── worker/
    └── worker.py
```

1️⃣ Arquivo: .env
Crie o arquivo .env na raiz da pasta 02-app-python-env. O Docker Compose lê este arquivo automaticamente para substituir variáveis expressas com ${NOME_DA_VARIAVEL} no compose.yaml:

```text
# Configurações do Host e da Aplicação
APP_PORT=5000
APP_ENV=desenvolvimento
SECRET_KEY=chave_super_secreta_treinamento_2026

# Configurações do Worker Interno
WORKER_LOG_LEVEL=DEBUG
```
2️⃣ Arquivos da API Flask (api/)
Crie a pasta api e insira os 3 arquivos abaixo dentro dela:

api/requirements.txt

```text
flask==3.0.0
```

api/Dockerfile

```dockerfile
FROM python:3.11-slim

WORKDIR /app

# Copia e instala as dependências
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copia o código fonte da aplicação
COPY main.py .

# EXPOSE no Dockerfile documenta a porta padrão que o processo escuta
EXPOSE 5000

CMD ["python", "main.py"]
```

api/main.py

```python
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
```

3️⃣ Arquivo do Worker Interno (worker/)
Crie a pasta worker e adicione o script worker.py:

worker/worker.py

```python
import os
from http.server import HTTPServer, BaseHTTPRequestHandler

LOG_LEVEL = os.getenv("WORKER_LOG_LEVEL", "INFO")

class SimpleWorkerHandler(BaseHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)
        self.send_header('Content-Type', 'text/plain; charset=utf-8')
        self.end_headers()
        response = f"Worker Interno Respondendo! [LogLevel: {LOG_LEVEL}]"
        self.wfile.write(response.encode('utf-8'))

if __name__ == '__main__':
    print(f"Iniciando Worker na porta interna 8000 (LogLevel: {LOG_LEVEL})...")
    server = HTTPServer(('0.0.0.0', 8000), SimpleWorkerHandler)
    server.serve_forever()
```

4️⃣ Arquivo: compose.yaml
Na raiz da pasta 02-app-python-env, crie o arquivo compose.yaml:

```yaml
services:
  # ---------------------------------------------------------------------------
  # SERVIÇO 1: API Web Pública
  # Demonstrando BUILD, PORTS e ENVIRONMENT (.env)
  # ---------------------------------------------------------------------------
  api:
    # 1. BUILD: Compila uma imagem própria a partir do Dockerfile local
    build:
      context: ./api
      dockerfile: Dockerfile

    container_name: python-api-app

    # 2. PORTS: Mapeia e PUBLICA a porta para acesso externo (Host -> Container)
    # Sintaxe: "PORTA_DO_HOST:PORTA_DO_CONTAINER"
    # O valor ${APP_PORT} é lido automaticamente do arquivo .env (ex: 5000)
    ports:
      - "${APP_PORT}:5000"

    # 3. ENVIRONMENT: Injeta variáveis de ambiente no container
    environment:
      - APP_ENV=${APP_ENV}
      - SECRET_KEY=${SECRET_KEY}
      - WORKER_URL=http://worker-interno:8000 # Usa o nome do serviço como Hostname

    restart: unless-stopped

  # ---------------------------------------------------------------------------
  # SERVIÇO 2: Worker de Processamento Interno
  # Demonstrando IMAGE e EXPOSE
  # ---------------------------------------------------------------------------
  worker-interno:
    # 1. IMAGE: Usa uma imagem oficial pronta do Docker Hub diretamente
    image: python:3.11-slim

    container_name: python-worker-app

    working_dir: /app
    volumes:
      - ./worker:/app:ro

    command: python worker.py

    # 2. EXPOSE: Abre a porta APENAS para os containers da mesma rede Docker.
    # NÃO mapeia para a máquina host (localhost:8000 NÃO vai funcionar).
    expose:
      - "8000"

    environment:
      - WORKER_LOG_LEVEL=${WORKER_LOG_LEVEL}

    restart: unless-stopped
```

Passo 1: Explicar o build e subir a stack
Execute o comando de construção e inicialização:

```bash
docker compose up -d --build
```

O que destacar:
O Docker Compose compilará o Dockerfile da api (etapa de build) criando uma imagem local.
O serviço worker-interno baixará diretamente a imagem python:3.11-slim do Docker Hub (image).

Passo 2: Testar a API pública (ports)

Acesse no navegador ou terminal:

```bash
curl http://localhost:5000
```

O que destacar: A API responde na porta 5000 porque usamos a diretiva ports mapeando a porta da máquina host para a porta do container. Note também que os valores de APP_ENV e SECRET_KEY foram injetados a partir do arquivo .env.

Passo 3: Demonstrar a diferença entre ports vs expose

Tentativa de acesso direto ao Worker pelo Host (deve falhar):

```bash
curl http://localhost:8000
```

Resultado esperado: Conexão recusada / Falha de conexão.
Explicação: O worker-interno utiliza expose: - "8000", portanto a porta está fechada para o mundo externo/host.

Acesso ao Worker via comunicação interna entre containers:

```bash
curl http://localhost:5000/status-worker
```

Resultado esperado: {"explicacao": "A API (com porta pública 5000) acessou o Worker...", "status": "sucesso"}

Explicação: A API Flask chamou http://worker-interno:8000 internamente. Como ambos estão na rede padrão criada pelo Compose, a porta liberada por expose permitiu o tráfego restrito entre containers.

Passo 4: Alterar o arquivo .env sem mexer no compose.yaml
Edite o arquivo .env:

```text
APP_PORT=5000
APP_ENV=homologacao
SECRET_KEY=nova_chave_alterada_2026
WORKER_LOG_LEVEL=WARNING
```

Recarregue os containers para aplicar as novas variáveis:

```bash
docker compose up -d
```

Teste novamente em http://localhost:5000 e veja que as novas configurações do .env foram aplicadas dinamicamente.

Passo 5: Limpeza

```bash
docker compose down
```

### 03-stack-banco-dados

📂 Estrutura de Pastas do Projeto

```text
03-stack-banco-dados/
├── .env.db
├── compose.yaml
└── web/
    ├── Dockerfile
    ├── app.py
    └── requirements.txt
```

1️⃣ Arquivo: .env.db
Crie o arquivo .env.db na raiz da pasta 03-stack-banco-dados. Ele centraliza as variáveis do banco de dados para reutilização no container do PostgreSQL e na API:

```text
POSTGRES_USER=appuser
POSTGRES_PASSWORD=segredo_postgres_2026
POSTGRES_DB=treinamento_db
```

2️⃣ Arquivos da Aplicação Web (web/)
Crie a pasta web e adicione os 3 arquivos abaixo:

web/requirements.txt

```text
flask==3.0.0
psycopg2-binary==2.9.9
```

web/Dockerfile

```dockerfile
FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY app.py .

EXPOSE 5000

CMD ["python", "app.py"]
```

web/app.py

```python
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
```

3️⃣ Arquivo: compose.yaml
Na raiz da pasta 03-stack-banco-dados, crie o arquivo compose.yaml:

```yaml
services:
  # ---------------------------------------------------------------------------
  # SERVIÇO 1: Aplicação Web (Flask)
  # ---------------------------------------------------------------------------
  web:
    build: ./web
    container_name: web-db-app
    ports:
      - "5000:5000"
    
    # ENV_FILE: Carrega o arquivo de variáveis do banco diretamente
    env_file:
      - .env.db
    environment:
      - DB_HOST=db-postgres # Nome do serviço do banco na rede interna

    # NETWORKS: O serviço web está presente nas duas redes (faz a ponte)
    networks:
      - frontend-net
      - backend-net

    restart: unless-stopped

  # ---------------------------------------------------------------------------
  # SERVIÇO 2: Banco de Dados PostgreSQL
  # ---------------------------------------------------------------------------
  db-postgres:
    image: postgres:16-alpine
    container_name: postgres-db-app
    
    # O PostgreSQL utiliza automaticamente as variáveis POSTGRES_USER,
    # POSTGRES_PASSWORD e POSTGRES_DB fornecidas pelo env_file
    env_file:
      - .env.db

    # VOLUMES: Named Volume para garantir a persistência dos dados do SGBD
    volumes:
      - pgdata:/var/lib/postgresql/data

    # NETWORKS: Fica isolado APENAS na rede backend (inacessível externamente)
    networks:
      - backend-net

    restart: unless-stopped

# -----------------------------------------------------------------------------
# BLOCOS DE INFRAESTRUTURA GLOBAIS (VOLUMES E NETWORKS)
# -----------------------------------------------------------------------------

# 1. NAMED VOLUMES: Gerenciados pelo Docker engine em área dedicada do SO
volumes:
  pgdata:
    driver: local

# 2. NETWORKS: Redes virtuais independentes para isolamento de tráfego
networks:
  frontend-net:
    driver: bridge
  backend-net:
    driver: bridge
```

Passo 1: Subir a infraestrutura

```bash
docker compose up -d
```

Passo 2: Demonstrar o isolamento de rede (networks)
Acesse a API pelo Host: http://localhost:5000

A requisição chega à API porque o serviço web faz parte da rede frontend-net e mapeia a porta 5000.

Tente conectar diretamente no PostgreSQL via Host:

```bash
psql -h localhost -U appuser -d treinamento_db
```

Resultado esperado: Conexão recusada / Timeout.

O que destacar: O banco de dados não declara a diretiva ports e está inserido estritamente na rede privada backend-net. Apenas containers que pertencem à backend-net (como a web) conseguem resolver o hostname db-postgres e se comunicar com a porta 5432.

Passo 3: Demonstrar a persistência com Named Volumes (volumes)
Atualize a página http://localhost:5000 no navegador cerca de 5 vezes. Note que o campo total_registros_persistidos vai para 5.

Destrua os containers sem apagar o volume:

```bash
docker compose down
```

Suba os containers novamente:

```bash
docker compose up -d
```

Atualize a página novamente em http://localhost:5000.

O que destacar: O contador estará em 6. Os dados não foram perdidos quando o container do banco foi removido porque o Named Volume pgdata continuou existindo no gerenciador do Docker no Host.

Passo 4: Apagando o volume nomeado (reset completo)
Para mostrar a diferença de lifecycle de um volume:

```bash
docker compose down -v
```

O que destacar: A flag -v (ou --volumes) remove explicitamente os Named Volumes declarados. Ao subir a stack novamente com docker compose up -d, o banco iniciará zerado (contador voltando para 1).

### 04-resiliencia-dependencias

📂 Estrutura de Pastas do Projeto

```text
04-resiliencia-dependencias/
├── compose.yaml
└── app/
    ├── Dockerfile
    ├── main.py
    └── requirements.txt
```

1️⃣ Arquivos da Aplicação (app/)
Crie a pasta app e adicione os 3 arquivos abaixo:

app/requirements.txt

```text
flask==3.0.0
psycopg2-binary==2.9.9
redis==5.0.1
```

app/Dockerfile

```dockerfile
FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY main.py .

EXPOSE 5000

CMD ["python", "main.py"]
```

app/main.py

```python
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
```

2️⃣ Arquivo: compose.yaml
Na raiz da pasta 04-resiliencia-dependencias, crie o arquivo compose.yaml:

```yaml
services:
  # ---------------------------------------------------------------------------
  # SERVIÇO 1: Banco de Dados PostgreSQL com Healthcheck
  # ---------------------------------------------------------------------------
  db:
    image: postgres:16-alpine
    container_name: resiliencia-db
    environment:
      POSTGRES_USER: admin
      POSTGRES_PASSWORD: secretpassword
      POSTGRES_DB: app_db

    # HEALTHCHECK: Garante que o serviço só é considerado pronto quando aceitar conexões SQL
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U admin -d app_db"]
      interval: 5s       # Executa o teste a cada 5 segundos
      timeout: 5s        # Tempo limite para resposta do teste
      retries: 5         # Quantidade de tentativas antes de marcar como 'unhealthy'
      start_period: 10s  # Tolerância inicial de boot para o container subir o processo

    restart: unless-stopped

  # ---------------------------------------------------------------------------
  # SERVIÇO 2: Cache Redis com Healthcheck
  # ---------------------------------------------------------------------------
  cache:
    image: redis:7-alpine
    container_name: resiliencia-cache

    # HEALTHCHECK: Valida o Redis via comando ping interno
    healthcheck:
      test: ["CMD-SHELL", "redis-cli ping | grep PONG"]
      interval: 5s
      timeout: 3s
      retries: 5
      start_period: 5s

    restart: unless-stopped

  # ---------------------------------------------------------------------------
  # SERVIÇO 3: API Python dependente das condições dos serviços auxiliares
  # ---------------------------------------------------------------------------
  api:
    build: ./app
    container_name: resiliencia-api
    ports:
      - "5000:5000"
    environment:
      - DB_HOST=db
      - DB_USER=admin
      - DB_PASS=secretpassword
      - DB_NAME=app_db
      - REDIS_HOST=cache

    # DEPENDS_ON com CONDITION: A ordem de boot só é liberada quando o
    # Healthcheck dos serviços dependentes retornar 'healthy' (resolve a race condition)
    depends_on:
      db:
        condition: service_healthy
      cache:
        condition: service_healthy

    # RESTART: Política on-failure com retentativas em falha de execução
    restart: on-failure:3
```

Passo 1: Subir a stack observando o status dos containers
Execute o comando de subida e acompanhe a criação:

```bash
docker compose up -d
```

Acompanhe a alteração de estados em tempo real com o comando:

```bash
docker compose ps
```

O que destacar:

Note que no campo STATUS dos containers db e cache aparecerá primeiro (health: starting).

O container resiliencia-api ficará na fila aguardando.

Assim que db e cache mudarem para (healthy), o Compose acionará o container da api.

Passo 2: Explicar o conceito de Race Condition
Sem o condition: service_healthy: O Docker Compose apenas inicia o container dependente assim que o container do banco começa a rodar (status running). Como o processo interno do PostgreSQL leva alguns segundos para inicializar as tabelas e aceitar portas, a API iria quebrar por não conseguir se conectar.

Com o condition: service_healthy: O Compose aguarda o teste do pg_isready passar com sucesso dentro do banco.

Passo 3: Validar a API
Acesse http://localhost:5000 via navegador ou terminal:

```bash
curl http://localhost:5000
```

O que destacar: A resposta indicará que tanto o Redis quanto o PostgreSQL receberam escrita e leitura na primeira tentativa sem falhas de inicialização.

Passo 4: Simular falha e testar a política restart: on-failure
Simule uma queda forçada matando o container da API:

```bash
docker kill resiliencia-api
```

Verifique a reinicialização automática do container:

```bash
docker ps -a
```

O que destacar: A política restart: on-failure:3 tenta restabelecer o serviço em caso de saída com erro (código diferente de 0) limitando a 3 tentativas.

Passo 5: Limpeza

```bash
docker compose down -v
```

### 05-microservicos-override

📂 Estrutura de Pastas do Projeto

```text
05-microservicos-override/
├── compose.yaml
├── compose.override.yaml
├── api/
│   ├── Dockerfile
│   ├── main.py
│   └── requirements.txt
└── worker/
    ├── Dockerfile
    ├── worker.py
    └── requirements.txt
```

1️⃣ Arquivos da API (api/)
Crie a pasta api e insira os 3 arquivos abaixo:

api/requirements.txt

```text
flask==3.0.0
redis==5.0.1
```

api/Dockerfile

```dockerfile
FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

EXPOSE 5000

CMD ["python", "main.py"]
```

api/main.py

```python
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
```

2️⃣ Arquivos do Worker (worker/)
Crie a pasta worker e insira os 3 arquivos abaixo:

worker/requirements.txt

```text
redis==5.0.1
```

worker/Dockerfile

```dockerfile
FROM python:3.11-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["python", "worker.py"]
```

worker/worker.py

```python
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
```

3️⃣ Arquivo Base de Produção: compose.yaml
Na raiz da pasta 05-microservicos-override, crie o arquivo compose.yaml. Este arquivo representa a infraestrutura limpa e segura voltada para Produção:

```yaml
services:
  # ---------------------------------------------------------------------------
  # CACHE REDIS (Fila de Tarefas)
  # ---------------------------------------------------------------------------
  cache:
    image: redis:7-alpine
    container_name: ms-cache
    healthcheck:
      test: ["CMD-SHELL", "redis-cli ping | grep PONG"]
      interval: 5s
      timeout: 3s
      retries: 5
    networks:
      - internal-net
    restart: always

  # ---------------------------------------------------------------------------
  # API PRINCIPAL (Base de Produção: Sem exposição de porta direta, sem bind mount)
  # ---------------------------------------------------------------------------
  api:
    build: ./api
    container_name: ms-api
    environment:
      - REDIS_HOST=cache
      - APP_ENV=producao
      - FLASK_DEBUG=0
    depends_on:
      cache:
        condition: service_healthy
    networks:
      - internal-net
    restart: always

  # ---------------------------------------------------------------------------
  # WORKER DE BACKGROUND
  # ---------------------------------------------------------------------------
  worker:
    build: ./worker
    container_name: ms-worker
    environment:
      - REDIS_HOST=cache
      - APP_ENV=producao
    depends_on:
      cache:
        condition: service_healthy
    networks:
      - internal-net
    restart: always

networks:
  internal-net:
    driver: bridge
```

4️⃣ Arquivo de Desenvolvimento: compose.override.yaml
Na raiz da mesma pasta, crie o arquivo compose.override.yaml. O Docker Compose detecta esse nome padrão e aplica as sobrescritas automaticamente em modo de desenvolvimento local:

```yaml
# O Docker Compose combina este arquivo AUTOMATICAMENTE com o compose.yaml
# quando executado o comando 'docker compose up' em ambiente de desenvolvimento.

services:
  api:
    environment:
      - APP_ENV=desenvolvimento
      - FLASK_DEBUG=1
    # Sobrescrita 1: Publica a porta para acesso via localhost apenas em dev
    ports:
      - "5000:5000"
    # Sobrescrita 2: Monta o código local em tempo real (Hot Reload sem rebuild)
    volumes:
      - ./api:/app

  worker:
    environment:
      - APP_ENV=desenvolvimento
    # Sobrescrita 3: Monta o código do worker para refletir alterações sem re-compilar
    volumes:
      - ./worker:/app
```

Passo 1: Subir em Modo Desenvolvimento (Comportamento Padrão)

```bash
docker compose up -d --build
```

O que destacar aos alunos:

O Docker Compose unificou o compose.yaml + compose.override.yaml de forma automática.

Teste acessando http://localhost:5000. O JSON de resposta confirmará:

"ambiente_atual": "desenvolvimento"

"flask_debug": true

A porta 5000 está mapeada na máquina host devido à regra injetada pelo arquivo .override.

Passo 2: Demonstrar o Live Reload (Bind Mount do Override)
Abra o arquivo api/main.py na sua IDE.

Altere o texto da mensagem no endpoint index (ex: de "API Microserviços" para "API Microserviços - Modificada ao Vivo!").

Salve o arquivo e atualize o navegador em http://localhost:5000.

O que destacar: O container refletiu a mudança instantaneamente sem a necessidade de rodar docker compose build ou reiniciar o container.

Passo 3: Testar a comunicação entre Microserviços (API -> Redis -> Worker)
Em uma janela de terminal, acompanhe os logs do Worker:

```bash
docker compose logs -f worker
```

No navegador ou em outro terminal, adicione uma tarefa chamando a rota:

```bash
curl -X POST http://localhost:5000/add-task
```

Observe o log do Worker recebendo e processando a tarefa relatorio_mensal_pdf consumida da fila do Redis em tempo real.

Passo 4: Simular o Deploy de Produção (Ignorando o Override)
Explique que em pipelines de CI/CD ou servidores de produção, o arquivo compose.override.yaml não deve ser aplicado. Para isso, especifica-se apenas o arquivo base com a flag -f:

```bash
# 1. Parar a stack de dev
docker compose down

# 2. Subir apontando estritamente para o arquivo base de produção
docker compose -f compose.yaml up -d --build
```

O que destacar:

Teste o acesso em http://localhost:5000. A conexão será recusada!

Motivo: No compose.yaml base (produção), a diretiva ports foi omitida por questões de segurança (ex: a API seria exposta internamente apenas para um Proxy Reverso como Nginx/Traefik).

A mecânica do compose.override.yaml permite manter o arquivo principal limpo para produção, enquanto oferece conveniência para os desenvolvedores locais.

Passo 5: Limpeza Final

```bash
docker compose -f compose.yaml down -v
```

