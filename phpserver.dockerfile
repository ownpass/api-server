FROM php:7.0-fpm

RUN apt-get update \
        && apt-get install -y zlib1g-dev libicu-dev g++ \
        && docker-php-ext-install pdo pdo_mysql intl
RUN pecl install Xdebug \
    && docker-php-ext-enable xdebug

COPY ./ /var/www
RUN mkdir -p /var/www/data && chown -R www-data:www-data /var/www

VOLUME /var/www
#VOLUME /var/www/data
