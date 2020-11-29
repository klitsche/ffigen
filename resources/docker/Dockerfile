FROM php:7.4-cli as base

# enable ffi
RUN set -e; \
    apt-get update; \
    apt-get install -y --no-install-recommends git zip unzip wget libffi-dev; \
    docker-php-ext-configure ffi; \
    docker-php-ext-install -j$(nproc) ffi pcntl; \
    apt-get autoremove -y; \
    rm -rf /var/lib/apt/lists/*; \
    rm -rf /tmp/*;

# install libs for examples
RUN set -e; \
    apt-get update; \
    apt-get install -y --no-install-recommends uuid-dev libsnappy-dev; \
    apt-get autoremove -y; \
    rm -rf /var/lib/apt/lists/*; \
    rm -rf /tmp/*;

ARG LIBRDKAFKA_VERSION=v1.5.2
ENV LIBRDKAFKA_VERSION=$LIBRDKAFKA_VERSION
RUN git clone --branch "${LIBRDKAFKA_VERSION}" --depth 1 https://github.com/edenhill/librdkafka.git /tmp/librdkafka; \
    cd /tmp/librdkafka; \
    ./configure --prefix=/usr; \
    make; \
    make install; \
    rm -rf /tmp/librdkafka;

# install xdebug
ARG XDEBUG_VERSION=stable
RUN pecl install xdebug-${XDEBUG_VERSION}; \
    docker-php-ext-enable xdebug;

ENV COMPOSER_HOME /tmp
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo;

RUN useradd -Ms /bin/bash --user-group --uid 2000 phpdev; \
    mkdir /app; \
    chown phpdev -R /app; \
    chown phpdev -R /tmp;

USER phpdev

WORKDIR /app
