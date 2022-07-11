FROM node:latest AS node
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

# Instal Fish SHELL
RUN apt-get install -y fish
RUN chsh -s /usr/bin/fish

# Attemptn to enable Autocomplition for Symfony Console for Fish

#RUN apt-get install -y bash-completion

#RUN echo "export PATH=\$PATH:/root/.config/composer/vendor/bin/" >> /root/.profile
#RUN echo "eval \"\$(symfony-autocomplete)\"" >> /root/.profile

# Set Up Autocomplition for Fish
#RUN mkdir -p /root/.config/fish/completions
#RUN touch /root/.config/fish/completions/composer.fish
#RUN touch /root/.config/fish/completions/console.fish

#CMD /root/.config/composer/vendor/bin/symfony-autocomplete --shell=fish console > ~/.config/fish/completions/console.fish

#ENV SHELL /usr/bin/fish
#ENV LANG=C.UTF-8 LANGUAGE=C.UTF-8 LC_ALL=C.UTF-8

# Install Nodejs, NPM and Yarn
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN npm install --global yarn

WORKDIR /var/www/symfony5-sandbox

EXPOSE 9000
