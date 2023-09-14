FROM php:8.2.10-fpm-alpine3.18 as base

ENV PROJECT_DIR=/var/www/html
ENV PATH $PROJECT_DIR/bin:$PROJECT_DIR/vendor/bin:$PATH
ARG UID=1000
ARG GID=985
ENV UID=$UID
ENV GID=$GID

RUN apk add --no-cache --virtual .ext-deps \
        linux-headers postgresql-dev netcat-openbsd libzip-dev libssh-dev libpng-dev icu-dev oniguruma-dev libxml2-dev shadow && \
        docker-php-ext-configure pdo_pgsql && \
        docker-php-ext-configure opcache   && \
        docker-php-ext-configure sockets   && \
        docker-php-ext-configure bcmath    && \
        docker-php-ext-configure intl      && \
        docker-php-ext-configure dom       && \
        docker-php-ext-configure exif      && \
        docker-php-ext-install dom xml zip gd pdo_pgsql opcache sockets bcmath mbstring pdo intl exif

RUN apk add --no-cache --update --virtual buildDeps autoconf  \
        && apk add build-base libgit2-dev rabbitmq-c-dev \
        && pecl install amqp \
        && docker-php-ext-enable amqp \
        && apk del buildDeps

RUN chown -R ${UID}:${GID} ${PROJECT_DIR} \
    && usermod --uid ${UID} --home ${PROJECT_DIR} --shell /bin/bash www-data \
    && groupmod --gid ${GID} www-data

COPY .deployment/docker/app/config/php.ini /usr/local/etc/php/

FROM base as dependencies
RUN apk add --no-cache git && \
    curl https://getcomposer.org/download/2.6.2/composer.phar --output composer  && \
    cp composer /usr/bin/composer  && \
    chmod +x /usr/bin/composer

COPY composer.json composer.lock symfony.lock $PROJECT_DIR/

RUN mkdir /.composer \
    && mkdir -p $PROJECT_DIR/var \
    && chown -R www-data:www-data $PROJECT_DIR/var \
    && chmod +rw /.composer $PROJECT_DIR/

FROM dependencies as devel_dependencies

RUN mkdir -p ~/.composer
RUN composer install --prefer-dist --no-scripts

FROM dependencies as final_stage

WORKDIR $PROJECT_DIR

USER root

COPY --chown=www-data:www-data . $PROJECT_DIR
COPY --chown=www-data:www-data --from=devel_dependencies $PROJECT_DIR/vendor $PROJECT_DIR/vendor
COPY --chown=www-data:www-data --from=devel_dependencies $PROJECT_DIR/bin $PROJECT_DIR/bin

EXPOSE 9000

CMD ["php-fpm", "-F"]
