FROM nginx

ADD ./docker/web.vhost.conf /etc/nginx/conf.d/default.conf

ADD ./ssl/localhost.crt /etc/nginx/ssl.crt
ADD ./ssl/localhost.key /etc/nginx/ssl.key

WORKDIR /var/www

EXPOSE 80
EXPOSE 443
