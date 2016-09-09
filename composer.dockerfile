FROM php:7.0-cli

RUN apt-get update && apt-get install -y wget git unzip zlib1g-dev
RUN docker-php-ext-install pdo pdo_mysql zip

# grab gosu for easy step-down from root
ENV GOSU_VERSION 1.7
RUN set -x \
	&& wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture)" \
	&& wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture).asc" \
	&& export GNUPGHOME="$(mktemp -d)" \
	&& gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4 \
	&& gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu \
	&& rm -r "$GNUPGHOME" /usr/local/bin/gosu.asc \
	&& chmod +x /usr/local/bin/gosu \
	&& gosu nobody true

COPY docker/composer/install.sh /usr/bin/composer-install
COPY docker/composer/entry-point.sh /usr/bin/entry-point
RUN chmod +x /usr/bin/composer-install /usr/bin/entry-point
RUN composer-install && useradd composer && mkdir -p /var/www && chown -R composer:composer /var/www
COPY ./ /var/www

ENTRYPOINT ["/usr/bin/entry-point"]
CMD ["composer"]
