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

FROM mlocati/php-extension-installer:1.5 as extension_installer

FROM php:8.0-cli-alpine as base

ARG ALPINE_REPO=http://dl-cdn.alpinelinux.org/alpine/

RUN sed -i -r 's#^http.+/(.+/main)#'${ALPINE_REPO%/}'/\1#' /etc/apk/repositories \
    && sed -i -r 's#^http.+/(.+/community)#'${ALPINE_REPO%/}'/\1#' /etc/apk/repositories \
    && sed -i -r 's#^http.+/(.+/testing)#'${ALPINE_REPO%/}'/\1#' /etc/apk/repositories \
    && echo /etc/apk/repositories \
    && apk add --no-cache --update \
        bash \
        gnu-libiconv \
        htop \
        less

ENV \
    # Fix for iconv: https://github.com/docker-library/php/issues/240
    LD_PRELOAD="/usr/lib/preloadable_libiconv.so php" \
    PATH="${PATH}:/home/warp/app/vendor/bin"

COPY --from=extension_installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions \
        bcmath \
        intl \
        opcache \
        pcntl \
        pdo_pgsql \
        uuid \
        zip \
    && rm -f /usr/bin/install-php-extensions /var/cache/apk/*

COPY .infr/app/rootfs/ /

ARG UID=1000
ARG GID=1000

RUN addgroup -g $GID warp \
    && adduser -D -S -h /home/warp -s /bin/bash -G warp -u $UID warp \
    && mkdir -p /home/warp/app \
    && chown -R warp:warp /home/warp

COPY --from=composer --chown=$UID:$GID /app/vendor/ ./vendor/
COPY --chown=$UID:$GID ./ ./

RUN rm -rf .infr

EXPOSE 8888
CMD ["php", "-S", "0.0.0.0:8888", "resources/server.php"]

FROM base as development

COPY --from=extension_installer /usr/bin/install-php-extensions /usr/bin/
RUN apk add --no-cache --update git \
    && install-php-extensions xdebug \
    && rm -f /usr/bin/install-php-extensions

COPY --from=composer /usr/bin/composer /usr/local/bin/

WORKDIR /home/warp/app
USER warp

FROM base as production

WORKDIR /home/warp/app
USER warp
