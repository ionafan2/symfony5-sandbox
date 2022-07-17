FROM node:latest AS node
FROM php:8.1.8-fpm

RUN apt-get update \
    && apt-get install -y \
        openjdk-11-jdk \
        ca-certificates-java \
        ant \
        libmcrypt-dev \
        libpq-dev \
        libpng-dev \
        libzip-dev \
        libxslt-dev \
        libicu-dev \
        git \
        procps \
        vim \
        unzip

RUN pecl install xdebug apcu apcu mcrypt \
    && docker-php-ext-install \
        gd \
        exif \
        zip \
        opcache \
        pdo \
        pdo_pgsql \
        pgsql \
        pdo_mysql \
        mysqli \
        sockets \
        xsl \
        intl \
    && docker-php-ext-enable \
        xdebug \
        apcu \
        mcrypt \
        pdo_mysql

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.trigger_value=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.file_link_format=\"phpstorm://open?file=%f&line=%l\"" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
RUN cd /tmp && curl -sS https://getcomposer.org/installer | php && \
    mv /tmp/composer.phar /usr/local/bin/composer

# Instal Fish SHELL
RUN apt-get install -y fish
RUN chsh -s /usr/bin/fish

# Install Nodejs, NPM and Yarn
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN npm install --global yarn

# See https://stackoverflow.com/questions/69394632/webpack-build-failing-with-err-ossl-evp-unsupported
ENV NODE_OPTIONS=--openssl-legacy-provider

WORKDIR /var/www/symfony5-sandbox

EXPOSE 9000
