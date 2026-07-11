FROM php:8.3-apache

ARG APP_PUBLIC_DIR=front/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends default-mysql-client libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html

RUN printf '<VirtualHost *:80>\n    DocumentRoot /var/www/html/%s\n    <Directory /var/www/html/%s>\n        AllowOverride All\n        Require all granted\n    </Directory>\n    ErrorLog ${APACHE_LOG_DIR}/error.log\n    CustomLog ${APACHE_LOG_DIR}/access.log combined\n</VirtualHost>\n' "$APP_PUBLIC_DIR" "$APP_PUBLIC_DIR" > /etc/apache2/sites-available/000-default.conf