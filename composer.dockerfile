FROM php:7.0-cli

COPY docker/composer/install.sh /usr/bin/composer-install
RUN apt-get update && apt-get install -y wget git unzip zlib1g-dev
RUN docker-php-ext-install pdo pdo_mysql zip
RUN chmod +x /usr/bin/composer-install
RUN composer-install

COPY ./ /var/www

CMD composer
