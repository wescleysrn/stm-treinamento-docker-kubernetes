# 🔌 1. Integração Nginx Ingress & Nexus

Para expor o Nexus via nexus.stmcurso.com.br e permitir o uso como Docker Registry / Mirror, o Ingress Nginx precisa de duas portas:

8081: Interface Web do Nexus + Repositórios Maven/NPM.

8082: Porta do Docker Registry (Hosted / Group / Proxy Mirror).

## Arquivo no Nginx Ingress: conf.d/nexus.conf

```nginx
# -------------------------------------------------------------
# 1. Interface Web do Nexus (nexus.stmcurso.com.br)
# -------------------------------------------------------------
server {
    listen 80;
    server_name nexus.stmcurso.com.br;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name nexus.stmcurso.com.br;

    ssl_certificate     /etc/nginx/ssl/stm_curso.crt;
    ssl_certificate_key /etc/nginx/ssl/stm_curso_private_key.key;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Suporte para uploads grandes (imagens Docker)
    client_max_body_size 0;

    # Utiliza o DNS embutido do Docker para resolução dinâmica
    resolver 127.0.0.11 valid=30s;

    location / {
        set $upstream_nexus http://imd-nexus:8081;
        proxy_pass $upstream_nexus;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}

# -------------------------------------------------------------
# 2. Docker Registry Hosted / Group / Mirror (nexus.stmcurso.com.br:8082)
# -------------------------------------------------------------
server {
    listen 8082 ssl;
    server_name nexus.stmcurso.com.br;

    ssl_certificate     /etc/nginx/ssl/stm_curso.crt;
    ssl_certificate_key /etc/nginx/ssl/stm_curso_private_key.key;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    client_max_body_size 0;

    # Utiliza o DNS embutido do Docker para resolução dinâmica
    resolver 127.0.0.11 valid=30s;

    location / {
        set $upstream_docker_registry http://imd-nexus:8082;
        proxy_pass $upstream_docker_registry;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}
```

> ⚠️ Atenção: No docker-compose.yml do Ingress Nginx, lembre-se de expor a porta 8082:8082 e colocar o Ingress e o Nexus na mesma rede externa (ex: ingress-network).

## 📦 2. Arquivo docker-compose.yml (Corrigido)

```yaml
version: "3.9"

services:
  nexus:
    image: sonatype/nexus3:3.72.0
    container_name: imd-nexus
    restart: always
    user: "200:200"
    expose:
      - "8081" # Web UI / Maven / NPM (Acessível via rede Docker para o Ingress)
      - "8082" # Docker Registry / Proxy (Acessível via rede Docker para o Ingress)
    ports:
      - "9001:8081" # Mantido apenas se quiser acessar o Nexus diretamente via localhost:9001 (opcional/debug)
    volumes:
      - ./nexus_data:/nexus-data
    networks:
      - ingress-network
    deploy:
      resources:
        limits:
          cpus: "2.0"
          memory: "2G"

networks:
  ingress-network:
    external: true    
```

