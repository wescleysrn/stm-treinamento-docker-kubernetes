FROM python:3.11-slim

# Definir diretório de trabalho
WORKDIR /app

# Copiar dependências e instalar
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copiar o código da aplicação
COPY app.py .

# Criar usuário não-root para segurança
RUN useradd -u 10001 appuser
USER appuser

# Expor a porta da aplicação
EXPOSE 5000

# Comando de inicialização
CMD ["python", "app.py"]
