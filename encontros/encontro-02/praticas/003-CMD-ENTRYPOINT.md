## 

## 1. Prática simples: demonstrando o ciclo de execução

Vamos utilizar um pequeno container baseado em Node.js para simular uma aplicação que fica em execução, recebe SIGTERM e encerra de maneira controlada.

Objetivo da demonstração
Mostrar como o comando definido no Dockerfile influencia o processo iniciado pelo container e o encerramento da aplicação.

## 2. Prepare a aplicação de teste
Crie uma pasta chamada docker-sinais com estes dois arquivos.

Arquivo app.js:

```javascript
console.log(`Aplicação iniciada. PID: ${process.pid}`);

process.on("SIGTERM", () => {
  console.log("SIGTERM recebido. Encerrando corretamente...");

  setTimeout(() => {
    console.log("Aplicação encerrada.");
    process.exit(0);
  }, 1000);
});

setInterval(() => {
  console.log("Aplicação em execução...");
}, 2000);
```

Essa aplicação faz três coisas:

Informa seu PID quando inicia.

Exibe uma mensagem a cada dois segundos.

Quando recebe SIGTERM, mostra que recebeu o sinal e encerra após um segundo.

Isso facilita a visualização do ciclo de vida do processo.

## 3. Teste 1 — Exec form
Crie o arquivo Dockerfile.exec:

```dockerfile
FROM node:22-alpine
WORKDIR /app
COPY app.js .
CMD ["node", "app.js"]
```

Construa e execute:

```bash
docker build -f Dockerfile.exec -t demo-exec .
docker run -d --name teste-exec demo-exec
docker logs -f teste-exec
```

Você verá a aplicação iniciando e exibindo mensagens a cada dois segundos. Pressione Ctrl+C para sair da visualização dos logs — isso não encerra o container.

Agora, em outro terminal, execute:

```bash
docker stop -t 5 teste-exec
docker logs teste-exec
```

O resultado esperado é semelhante a:

```text
Aplicação iniciada. PID: 1
Aplicação em execução...
SIGTERM recebido. Encerrando corretamente...
Aplicação encerrada.
```

O PID mostrado pela aplicação deverá ser 1 nesse exemplo. Como o comando está na forma exec, o Node é iniciado diretamente como processo principal do container. O Docker envia o sinal de parada a esse processo, que pode tratar o SIGTERM e encerrar corretamente.

## 4. Teste 2 — Shell form

Agora crie outro arquivo, Dockerfile.shell:

```dockerfile
FROM node:22-alpine
WORKDIR /app
COPY app.js .
CMD node app.js
```

Execute:

```bash
docker build -f Dockerfile.shell -t demo-shell .
docker run -d --name teste-shell demo-shell
docker logs teste-shell
```

Verifique os processos:

```bash
docker top teste-shell
```

Você deverá observar o shell e o Node como processos distintos, embora a apresentação exata possa variar conforme o ambiente. A estrutura será semelhante a:

```text
/bin/sh -c node app.js
node app.js
```

Agora pare o container:

```bash
docker stop -t 3 teste-shell
docker logs teste-shell
```

Na forma shell, o processo principal é normalmente /bin/sh -c, que inicia o Node como processo filho. O sinal enviado pelo Docker não é necessariamente encaminhado ao Node. Por isso, você pode não ver a mensagem SIGTERM recebido e o container pode precisar esperar o tempo limite antes de ser encerrado à força.

> Ponto importante para explicar: isso não significa que a forma shell sempre falhará. Significa que o encaminhamento de sinais não é garantido da mesma maneira. Usar a forma exec evita esse shell intermediário.

## 5. Teste 3 — ENTRYPOINT + CMD
Agora vamos demonstrar como os dois comandos trabalham juntos.

Crie Dockerfile.ping:

```dockerfile
FROM alpine:3.22
ENTRYPOINT ["ping"]
CMD ["localhost"]
```

Construa e execute:

```bash
docker build -f Dockerfile.ping -t demo-ping .
docker run --rm demo-ping
```

O container executará, por padrão:

```bash
ping localhost
```

Agora execute:

```bash
docker run --rm demo-ping 127.0.0.1
```

O resultado será equivalente a executar ping 127.0.0.1. O ENTRYPOINT continua sendo ping, enquanto o argumento padrão definido em CMD foi substituído pelo argumento informado ao executar o container. Pressione Ctrl+C para interromper o teste interativo.

O quê ocorreu nesse exemplo ?

ENTRYPOINT ["ping"]: define o programa principal que será executado.

CMD ["localhost"]: define o argumento padrão.

docker run demo-ping 127.0.0.1: substitui os argumentos padrão, mas mantém o ENTRYPOINT.

Uma analogia simples: o ENTRYPOINT é a ferramenta que você escolhe; o CMD é a configuração padrão com a qual você a utiliza.

------------

Exemplo de aplicações praticas:

## 1. API em Node.js com Express

Cenário real: uma API REST que atende requisições HTTP.

A aplicação tem um arquivo server.js que inicia o servidor HTTP.

```dockerfile
FROM node:22-alpine
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

ENTRYPOINT ["node"]
CMD ["server.js"]
```

Execute a API:

```bash
docker build -t minha-api .
docker run --rm -p 3000:3000 minha-api
```

O Docker combina as instruções e executa:

```bash
node server.js
```

Agora imagine que você queira executar outro arquivo Node dentro da mesma imagem:

```bash
docker run --rm minha-api scripts/healthcheck.js
```

O comando efetivo será:

```bash
node scripts/healthcheck.js
```

Por que isso é útil?

ENTRYPOINT ["node"] define o runtime.

CMD ["server.js"] define o arquivo padrão.

O argumento fornecido no docker run substitui o arquivo padrão.

Essa abordagem é útil quando a imagem serve como um ambiente executável para diferentes scripts Node. Para uma imagem de produção dedicada exclusivamente à API, também é comum usar CMD ["node", "server.js"], sem ENTRYPOINT.

## 2. Django: API ou aplicação web em Python

Cenário real: uma aplicação Django que precisa iniciar o servidor ou executar tarefas administrativas.

Imagine que o projeto possui manage.py, como em uma aplicação Django convencional.

```dockerfile
FROM python:3.12-slim
WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

ENTRYPOINT ["python", "manage.py"]
CMD ["runserver", "0.0.0.0:8000"]
```

Inicie o servidor:

```bash
docker build -t minha-app-django .
docker run --rm -p 8000:8000 minha-app-django
```

O comando efetivo é:

```bash
python manage.py runserver 0.0.0.0:8000
```

Agora você pode reutilizar a imagem para executar migrations:

```bash
docker run --rm minha-app-django migrate
```

Ou abrir um shell interativo do Django:

```bash
docker run --rm -it minha-app-django shell
``` 

O comando muda, mas o executável e o arquivo principal continuam iguais.

Essa combinação é útil quando várias tarefas compartilham o mesmo ponto de entrada, como manage.py. Em produção, o servidor de desenvolvimento runserver deve ser substituído por um servidor WSGI ou ASGI apropriado, como Gunicorn ou Uvicorn, conforme a aplicação.

## 3. Laravel: migrations, filas e servidor web

Cenário real: uma aplicação PHP com tarefas de manutenção, processamento de filas e comandos do Artisan.

No Laravel, o comando artisan centraliza muitas operações do framework.

Dockerfile simplificado

```dockerfile
FROM php:8.3-cli
WORKDIR /var/www/html

COPY . .

ENTRYPOINT ["php", "artisan"]
CMD ["serve", "--host=0.0.0.0", "--port=8000"]
```

Para iniciar o servidor de desenvolvimento:

```bash
docker build -t minha-app-laravel .
docker run --rm -p 8000:8000 minha-app-laravel
```

O comando executado será equivalente a:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Para executar migrations:

```bash
docker run --rm minha-app-laravel migrate
```

Para processar filas:

```bash
docker run --rm minha-app-laravel queue:work
```

O aprendizado importante: uma mesma imagem pode servir a funções diferentes da aplicação. Em uma arquitetura com Docker Compose, você poderia usar a mesma imagem para o serviço web e para o worker, mudando os argumentos de execução.

> Nota: o Dockerfile acima é didático. Uma imagem Laravel real geralmente precisa instalar extensões PHP, dependências Composer e configurar permissões. Em produção, o servidor HTTP costuma ser fornecido por uma configuração apropriada com PHP-FPM e um servidor web, e não pelo artisan serve.

## 4. Spring Boot: aplicação Java

Cenário real: uma API Java empacotada em um arquivo JAR executável.

Cenário real: uma API Java empacotada em um arquivo JAR executável.

```dockerfile
FROM eclipse-temurin:21-jre
WORKDIR /app

COPY target/minha-api.jar app.jar

ENTRYPOINT ["java", "-jar", "/app/app.jar"]
CMD ["--server.port=8080"]
```

Execute:

```bash
docker build -t minha-api-java .
docker run --rm -p 8080:8080 minha-api-java
```

O comando efetivo será:

java -jar /app/app.jar --server.port=8080

Agora execute a aplicação em outra porta:

```bash
docker run --rm -p 9090:9090 minha-api-java --server.port=9090
```

O Docker substituirá os argumentos padrão do CMD, mantendo o executável definido no ENTRYPOINT.


