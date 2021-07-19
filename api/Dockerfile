FROM php:8.0-fpm-alpine3.13

ARG user
ARG uid

# production || development
ARG env

WORKDIR /var/www/api

RUN apk update \
 && apk upgrade \
 && apk add vim

RUN docker-php-ext-install pdo pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /bin/composer
RUN php -r "unlink('composer-setup.php');"

RUN cp /usr/local/etc/php/php.ini-${env} /usr/local/etc/php/php.ini

RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 40M/g' /usr/local/etc/php/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 64M/g' /usr/local/etc/php/php.ini

RUN adduser martin --home /home/${user} --uid ${uid} --disabled-password

USER ${user}
