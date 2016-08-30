FROM php:7.0-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install Xdebug \
    && docker-php-ext-enable xdebug

COPY ./ /var/www
RUN mkdir -p /var/www/data && chown -R www-data:www-data /var/www

VOLUME /var/www
#VOLUME /var/www/data
