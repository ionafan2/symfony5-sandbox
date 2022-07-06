FROM php:8.0.14-fpm

RUN mkdir -p /usr/share/man/man1

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

WORKDIR /var/www/symfony5-sandbox

EXPOSE 9000
