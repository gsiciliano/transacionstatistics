FROM nginx

ADD ./docker/dev/web.vhost.conf /etc/nginx/conf.d/default.conf

ADD ./ssl/ssl.crt /etc/nginx/ssl.crt
ADD ./ssl/ssl.key /etc/nginx/ssl.key

WORKDIR /var/www

EXPOSE 80
EXPOSE 443
