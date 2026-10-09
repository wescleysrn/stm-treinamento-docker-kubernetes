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
