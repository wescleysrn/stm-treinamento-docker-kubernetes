# 🚀 Estrutura do Projeto Nginx (Ingress com Volume local)

Abaixo está a estrutura completa do projeto para montar o container Nginx Ingress.

## Estrutura de Diretórios Recomendada

```text
ingress-nginx/
├── ssl/
│   ├── stm_curso.crt
│   └── stm_curso_private_key.key
├── conf.d/
│   ├── default.conf
│   ├── nexus.conf
│   └── sonar.conf
├── Dockerfile
└── docker-compose.yml
```

### 1. Dockerfile

O Dockerfile prepara a imagem do Nginx garantindo a inclusão das configurações básicas e a cópia padrão do certificado.

```dockerfile
FROM nginx:alpine

# Remove configurações padrão do Nginx
RUN rm /etc/nginx/conf.d/default.conf

# Cria pasta para armazenar os certificados SSL dentro da imagem
RUN mkdir -p /etc/nginx/ssl

# Copia os certificados gerados
COPY ssl/stm_curso.crt /etc/nginx/ssl/stm_curso.crt
COPY ssl/stm_curso_private_key.key /etc/nginx/ssl/stm_curso_private_key.key

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
```

### 2. VHosts no Diretório conf.d/

conf.d/nexus.conf

```nginx
server {
    listen 80;
    server_name nexus.stmcurso.com.br;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name nexus.stmcurso.com.br;

    ssl_certificate /etc/nginx/ssl/stm_curso.crt;
    ssl_certificate_key /etc/nginx/ssl/stm_curso_private_key.key;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        # Para fins de demonstração, pode redirecionar para um serviço interno ou container nexus
        # proxy_pass http://nexus_container:8081;
        default_type text/html;
        return 200 '<h1>Bem-vindo ao Nexus (HTTPS)</h1>';
    }
}
```

conf.d/sonar.conf

```nginx
server {
    listen 80;
    server_name sonar.stmcurso.com.br;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name sonar.stmcurso.com.br;

    ssl_certificate /etc/nginx/ssl/stm_curso.crt;
    ssl_certificate_key /etc/nginx/ssl/stm_curso_private_key.key;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        # proxy_pass http://sonarqube_container:9000;
        default_type text/html;
        return 200 '<h1>Bem-vindo ao SonarQube (HTTPS)</h1>';
    }
}
```

### 3. Subindo o Container com docker-compose.yml

Criar rede 

```bash
docker network create ingress-network
```

O uso de volume montado na pasta conf.d/ permite alterar, incluir ou remover Virtual Hosts em tempo de execução sem re-construir a imagem Docker.

```yaml
version: '3.8'

services:
  nginx-ingress:
    build: .
    container_name: ingress-nginx
    ports:
      - "80:80"
      - "443:443"
      - "8082:8082" # IMPORTANTE: Adicione a porta 8082 para o Docker Registry do Nexus!
    volumes:
      - ./conf.d:/etc/nginx/conf.d:ro
    networks:
      - ingress-network
    restart: always

networks:
  ingress-network:
    external: true
```

Para rodar:

```bash
docker-compose up -d --build
```

🌐 Configuração do hosts Local da Máquina
Para testar as chamadas locais resolvendo para o seu container Docker, adicione as entradas no arquivo hosts do seu sistema operacional.

Caminho do Arquivo
Windows: C:\Windows\System32\drivers\etc\hosts (Abrir Bloco de Notas como Administrador)

Linux / macOS: /etc/hosts (Editar com sudo nano /etc/hosts)

Adicionar ao final do arquivo:

```text
127.0.0.1  nexus.stmcurso.com.br
127.0.0.1  sonar.stmcurso.com.br
127.0.0.1  stmcurso.com.br
```

🧪 Testando
Abra o navegador e acesse [https://nexus.stmcurso.com.br](https://nexus.stmcurso.com.br) e [https://sonar.stmcurso.com.br](https://sonar.stmcurso.com.br).

O certificado será exibido como seguro (caso tenha importado o .crt no repositório de Autoridades Confiáveis).

