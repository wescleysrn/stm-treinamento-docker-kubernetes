FROM litespeedtech/openlitespeed:1.8.4-lsphp85

# Instala extensões adicionais necessárias
RUN apt-get update && apt-get install -y \
    lsphp85-mysql \
    lsphp85-redis \
    lsphp85-curl \
    lsphp85-intl \
    && rm -rf /var/lib/apt/lists/*

# OPcache já embutido
RUN echo "opcache.enable=1" >> /usr/local/lsws/lsphp85/etc/php/8.5/mods-available/opcache.ini \
 && echo "opcache.memory_consumption=256" >> /usr/local/lsws/lsphp85/etc/php/8.5/mods-available/opcache.ini \
 && echo "opcache.max_accelerated_files=30000" >> /usr/local/lsws/lsphp85/etc/php/8.5/mods-available/opcache.ini \
 && echo "opcache.validate_timestamps=0" >> /usr/local/lsws/lsphp85/etc/php/8.5/mods-available/opcache.ini \
 && echo "opcache.revalidate_freq=0" >> /usr/local/lsws/lsphp85/etc/php/8.5/mods-available/opcache.ini

# Diretórios internos do OLS
#RUN mkdir -p /tmp/lshttpd \
# && chown -R nobody:nogroup /tmp/lshttpd \
# && chown -R nobody:nogroup /usr/local/lsws/logs

# ⚠️ PERMISSÕES DO WORDPRESS (CRÍTICO)
#RUN mkdir -p /var/www/html/wp-content/uploads \
# && chown -R nobody:nogroup /var/www/html \
# && find /var/www/html -type d -exec chmod 755 {} \; \
# && find /var/www/html -type f -exec chmod 644 {} \; 

# Para testar com performance melhor, sem conversão de sistema de arquivo na pasta volume
# COPY wordpress /var/www/html

RUN mkdir -p /var/www/html \
    && chmod -R 777 /var/www/html

WORKDIR /var/www/html
