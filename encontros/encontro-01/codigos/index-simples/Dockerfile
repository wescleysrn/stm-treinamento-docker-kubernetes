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
