FROM debian:bookworm-slim

ARG megaraid_wrapper_from;

RUN apt-get update
RUN apt-get -y upgrade

RUN apt-get -y install lsb-release unzip gnupg cron curl supervisor nginx
RUN apt-get -y install php8.2-fpm php8.2-zip php8.2-sqlite3 php8.2-curl php8.2-pdo-sqlite php8.2-zmq

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN touch /var/log/php8.2-fpm.log
RUN mkdir -p /var/run/php

# Copy new entry point file
COPY docker-deploy-entrypoint-custom /usr/local/bin/

# nginx vhost
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# php fpm config
COPY ./php/www.conf /etc/php/8.2/fpm/pool.d/www.conf

# php ini
COPY ./php/local.ini /etc/php/8.2/fpm/conf.d

#php opcache.ini
#COPY ./php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy supervisor config
COPY ./supervisor/app-supervisor.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/local/bin/docker-deploy-entrypoint-custom"]

EXPOSE 80

STOPSIGNAL SIGTERM