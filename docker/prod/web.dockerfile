FROM nginx

ADD ./docker/prod/web.vhost.conf /etc/nginx/conf.d/default.conf

ADD ./ssl/ssl.crt /etc/nginx/ssl.crt
ADD ./ssl/ssl.key /etc/nginx/ssl.key

EXPOSE 80
EXPOSE 443
