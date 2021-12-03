FROM ubuntu:latest

RUN apt-get update && apt-get install -y supervisor

RUN mkdir -p /var/log/supervisor

COPY supervisor/laravel-worker.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]
