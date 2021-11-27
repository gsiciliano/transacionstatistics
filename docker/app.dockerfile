FROM php:7.4.2-fpm

ARG USER
ARG USERID
ARG GID
ARG OS

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client \
    openssl zip unzip git nano wget libaio-dev iputils-ping

RUN apt-get update && apt-get install -y xvfb libfontconfig wkhtmltopdf libxslt1-dev libzip-dev p7zip-full

RUN docker-php-ext-install pdo_mysql \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip \
    && mkdir -p /home/$USER \
    && mkdir -p /home/$USER/.composer \
    && if [ "$OS" = "Linux" ]; then \
        groupadd -g $GID $USER; \
        useradd -u $USERID -g $USER $USER -d /home/$USER; \
        chown $USER:$USER /home/$USER; \
        chown -R $USER:$USER /home/$USER/.composer; \
        chown $USER:$USER /var/www; \
    fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
USER $USER




