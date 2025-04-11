ARG ENVIRONMENT=development

FROM serversideup/php:8.4-fpm-alpine AS base

ENV SHOW_WELCOME_MESSAGE=false

USER root

RUN install-php-extensions intl

FROM base AS development

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/conf.d/php.ini-development

ARG USER_ID
ARG GROUP_ID

# Use serversideup script to change the UID and GID of www-data
RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
	docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service fpm

RUN chown -R $USER_ID:$GROUP_ID /composer

FROM base AS production

RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/conf.d/php.ini-production

COPY --chown=www-data:www-data . /var/www/html

FROM ${ENVIRONMENT}

USER www-data

WORKDIR /var/www/html