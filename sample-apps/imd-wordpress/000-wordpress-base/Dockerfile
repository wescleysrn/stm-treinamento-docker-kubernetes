#FROM wordpress:6.9.0-php8.5-apache
# FROM wordpress:6.9.0-php8.5-fpm Obs.: Incompatível com o plugin WPForms que impede instalação do Blocksy
FROM wordpress:6.8.3-php8.5-fpm

# Dependências adicionais
RUN apt-get update && apt-get install -y \
    unzip \
    vim \
    less \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# OPcache já existe no PHP 8.5 — apenas configuramos
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.enable_cli=0" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.max_accelerated_files=30000" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini

COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Copia o entrypoint
#COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
#RUN chmod +x /usr/local/bin/docker-entrypoint.sh

#ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

WORKDIR /var/www/html
