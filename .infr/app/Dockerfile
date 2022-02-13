FROM composer:2 as composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-progress \
    --no-interaction \
    --ignore-platform-reqs \
    --prefer-dist

FROM php:8.0-cli-alpine3.13

ENV \
    # Fix for iconv: https://github.com/docker-library/php/issues/240
    LD_PRELOAD="/usr/lib/preloadable_libiconv.so php" \
    PATH="${PATH}:/home/space/app/vendor/bin"

COPY --from=composer /usr/bin/composer /usr/local/bin/
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --update \
        bash \
        less \
        git \
        gnu-libiconv \
    && install-php-extensions bcmath intl opcache zip pcntl pdo_pgsql xdebug-stable uuid-stable

COPY .infr/app/rootfs/ /

ARG UID=1000
ARG GID=1000

RUN addgroup -g $GID onfire \
    && adduser -D -S -h /home/space -s /bin/bash -G onfire -u $UID space \
    && mkdir -p /home/space/app \
    && chown -R space:onfire /home/space

WORKDIR /home/space/app

COPY --from=composer --chown=$UID:$GID /app/vendor/ ./vendor/
COPY --chown=$UID:$GID ./ ./

RUN rm -rf .infr

USER space
EXPOSE 8888
CMD ["php", "-S", "0.0.0.0:8888", "resources/server.php"]
