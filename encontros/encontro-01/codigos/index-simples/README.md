# Encontro 1: Pratica Bonus

---

## 🎯 Objetivos de Aprendizagem

Ao final deste encontro, você será capaz de:
1. Navegar e descobrir comandos e opções no Docker usando a ajuda integrada (`--help`).
2. Compartilhar arquivos locais com containers em tempo de execução através de montagens de diretórios.
3. Compreender a estrutura de camadas e escrever um `Dockerfile`.
4. Entender a natureza efêmera dos containers.
5. Criar e gerenciar **Volumes Docker** para persistência de dados em bancos de dados (ex: PostgreSQL).

---

## 🔍 Partindo do Zero: Utilizando o `--help` da CLI

O Docker CLI possui uma documentação integrada completa. Você pode descobrir a estrutura de qualquer comando e suas opções (`flags`) sem sair do terminal.

### Estrutura Geral dos Comandos

```bash
docker [COMANDO] [SUBCOMANDO] [OPÇÕES]
```

Exemplos Práticos de Descoberta
Listar todos os comandos disponíveis:

```bash
docker --help
```

Descobrir como gerenciar e rodar containers:

```bash
docker run --help
```

Entender flags comuns no docker run:

-d, --detach: Executa o container em segundo plano (background).

-p, --publish: Mapeia portas do host para o container (PORTA_HOST:PORTA_CONTAINER).

-v, --volume: Monta um volume ou diretório local no container.

-e, --env: Define variáveis de ambiente.

--name: Atribui um nome customizado ao container.

Explorar comandos de gerenciamento específico:

```bash
docker volume --help
docker network --help
docker container --help
```

## 📄 1. Passando o index.html via Linha de Comando (Bind Mount)
Antes de construir uma imagem própria, você pode servir arquivos locais diretamente em um container Nginx utilizando um Bind Mount (-v).

### Passo 1: Criar o arquivo index.html localmente
Crie o arquivo index.html no seu diretório atual:

```html
<!-- index.html -->
<html>
  <body>
    <h1>Minha Aplicacao Dockerizada!</h1>
    <p>Imagem construida com sucesso durante o Treinamento do STM.</p>
  </body>
</html>
```

### Passo 2: Executar o Nginx mapeando o arquivo local

Execute o Nginx padrão e substitua o diretório de páginas pelo seu diretório atual ($(pwd) no Linux/macOS ou %cd% no Windows Command Prompt, ou ${PWD} no Visual Studio terminal):

```bash
docker run -d \
  --name nginx-bind \
  -p 8080:80 \
  -v ${PWD}/index.html:/usr/share/nginx/html/index.html \
  nginx:alpine
```

```bash
docker run -d --name nginx-bind -p 8080:80 -v ${PWD}/index.html:/usr/share/nginx/html/index.html nginx:alpine
```

Acesse http://localhost:8080 no seu navegador para ver sua página sendo servida.

Remova o container após o teste:

```bash
docker stop nginx-bind && docker rm nginx-bind
```

## 🐳 2. Empacotando com Dockerfile
Para distribuir sua aplicação de forma autônoma sem depender de arquivos no sistema host, criamos uma nova imagem customizada contendo a aplicação.

### Passo 1: Escrever o Dockerfile

Crie o arquivo chamado Dockerfile no mesmo diretório:

```dockerfile
# Define a imagem base
FROM nginx:alpine

# Define o diretório de trabalho padrão do Nginx
WORKDIR /usr/share/nginx/html

# Copia o arquivo local para dentro da imagem
COPY index.html .

# Documenta a porta que será exposta
EXPOSE 80

# Comando executado ao iniciar o container
CMD ["nginx", "-g", "daemon off;"]
```

Passo 2: Construir a imagem (Build)

```bash
docker build -t site-stm:1.0 .
```

Confirme a criação da imagem:

```bash
docker images
```

### Passo 3: Executar a imagem construída

```bash
docker run -d --name meu-site -p 8080:80 site-stm:1.0
```

Acesse http://localhost:8080 e verifique o funcionamento.

## 💾 3. Persistência de Dados: PostgreSQL e Volumes
Os containers por padrão são efêmeros: qualquer dado gravado durante sua execução é destruído assim que o container é removido.

⚠️ Demonstração 1: Perda de Dados Sem Volume
Vamos subir um banco PostgreSQL sem volume e comprovar que os dados somem ao remover o container.

### 1. Iniciar o PostgreSQL sem volume:

```bash
docker run -d \
  --name pg-sem-volume \
  -e POSTGRES_PASSWORD=senha123 \
  postgres:15-alpine
```

```bash
docker run -d --name pg-sem-volume -e POSTGRES_PASSWORD=senha123 postgres:15-alpine
```

### 2. Acessar o banco via docker exec e criar uma tabela/dados:

```bash
docker exec -it pg-sem-volume psql -U postgres
```

Dentro do terminal do PostgreSQL (psql), execute:

```sql
CREATE TABLE usuarios (id SERIAL PRIMARY KEY, nome VARCHAR(50));
INSERT INTO usuarios (nome) VALUES ('Servidor STM');
SELECT * FROM usuarios;
\q
```

3. Remover o container:

```bash
docker stop pg-sem-volume
docker rm pg-sem-volume
```

4. Subir um novo container PostgreSQL:

```bash
docker run -d \
  --name pg-sem-volume \
  -e POSTGRES_PASSWORD=senha123 \
  postgres:15-alpine
```

```bash
docker run -d --name pg-sem-volume -e POSTGRES_PASSWORD=senha123 postgres:15-alpine
```

5. Verificar se os dados persistem:

```bash
docker exec -it pg-sem-volume psql -U postgres -c "SELECT * FROM usuarios;"
```

> ❌ Resultado: O comando retornará erro indicando que a tabela usuarios não existe. Os dados foram perdidos!

Limpe o container de teste:

```bash
docker stop pg-sem-volume && docker rm pg-sem-volume
```

## ✅ Demonstração 2: Persistindo Dados com Volumes Docker
Volumes são diretórios gerenciados pelo próprio Docker no host, desacoplados do ciclo de vida dos containers.

### 1. Criar um Volume Docker:

```bash
docker volume create pg-dados
```

Listar volumes:

```bash
docker volume ls
```

### 2. Subir o PostgreSQL vinculando o Volume ao diretório de dados:

```bash
docker run -d \
  --name pg-com-volume \
  -v pg-dados:/var/lib/postgresql/data \
  -e POSTGRES_PASSWORD=senha123 \
  postgres:15-alpine
```

```bash
docker run -d --name pg-com-volume -v pg-dados:/var/lib/postgresql/data -e POSTGRES_PASSWORD=senha123 postgres:15-alpine
```

### 3. Acessar e cadastrar dados:

```bash
docker exec -it pg-com-volume psql -U postgres
```

No psql:

```sql
CREATE TABLE usuarios (id SERIAL PRIMARY KEY, nome VARCHAR(50));
INSERT INTO usuarios (nome) VALUES ('Servidor Persistido');
SELECT * FROM usuarios;
\q
```

### 4. Destruir o container:

```bash
docker stop pg-com-volume
docker rm pg-com-volume
```

### 5. Criar um NOVO container apontando para o MESMO volume:

```bash
docker run -d \
  --name pg-com-volume \
  -v pg-dados:/var/lib/postgresql/data \
  -e POSTGRES_PASSWORD=senha123 \
  postgres:15-alpine
```

```bash
docker run -d --name pg-com-volume -v pg-dados:/var/lib/postgresql/data -e POSTGRES_PASSWORD=senha123 postgres:15-alpine
```

### 6. Validar que os dados foram preservados:

```bash
docker exec -it pg-com-volume psql -U postgres -c "SELECT * FROM usuarios;"
```

> ✔️ Resultado: A tabela e o registro 'Servidor Persistido' continuarão intactos!

## 🧹 Limpeza do Ambiente

Para remover os containers e volumes criados nos testes:

```bash
docker stop pg-com-volume meu-site
docker rm pg-com-volume meu-site
docker volume rm pg-dados
docker rmi site-stm:1.0
```
