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
