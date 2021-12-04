FROM php:7.4.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client \
    openssl zip unzip git nano wget libaio-dev iputils-ping

RUN apt-get update && apt-get install -y xvfb libfontconfig wkhtmltopdf libxslt1-dev libzip-dev p7zip-full

ADD ./ /var/www/

RUN docker-php-ext-install pdo_mysql \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip \
    && mkdir -p /home/www-data/.composer \
    && chmod +x /var/www/init.sh \
    && chmod -R 777 /var/www/storage

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www





