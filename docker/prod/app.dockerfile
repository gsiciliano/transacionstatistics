FROM php:7.4.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client \
    openssl zip unzip git wget libaio-dev xvfb libfontconfig wkhtmltopdf \
    libxslt1-dev libzip-dev p7zip-full

ADD ./src /var/www/
ADD ./.env /var/www/.env

RUN docker-php-ext-install pdo_mysql \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip \
    && mkdir -p /home/www-data/.composer \
    && chown -R www-data:www-data /var/www \
    && chmod +x /var/www/init.sh \
    && chmod -R 777 /var/www/storage

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

USER www-data





