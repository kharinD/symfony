FROM php:7.4-fpm-alpine

ARG APP_ENV

# Installing and updating libs
RUN apk add --virtual build-deps $PHPIZE_DEPS \
    && apk add --no-cache \
        autoconf \
        make \
        gcc \
        g++ \
        git \
        libtool \
        icu-dev \
        curl-dev \
        freetype-dev \
        php7-mysqli \
        php7-pgsql \
        php7-redis \
        postgresql-dev \
        imagemagick \
        imagemagick-dev \
        pcre-dev \
        libmcrypt-dev \
        libmcrypt \
        libwebp-dev \
        libxpm-dev \
        freetype-dev \
        jpeg-dev \
        libwebp-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        gd-dev \
        zip \
        libxslt-dev \
        libzip-dev \
        libxml2-dev \
        libintl \
        libltdl \
        libjpeg-turbo-utils \
        shadow \
        rabbitmq-c-dev \
        libssh-dev \
        gmp-dev \
        gnu-libiconv

RUN if [ "$APP_ENV" = "dev" ]; then pecl install xdebug-2.7.1 && docker-php-ext-enable xdebug; fi

# Iconv fix. В образе php-alpine либа не конфигурируется
# https://github.com/docker-library/php/issues/240
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Install PHP extensions
RUN docker-php-ext-configure zip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install zip \
        gd \
        soap \
        intl \
        bcmath \
        exif \
        opcache \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pcntl \
        sysvmsg \
        sysvshm \
        sysvsem \
        sockets \
        xsl \
        gmp \
    && pecl install \
        imagick \
        amqp \
        redis \
    && docker-php-ext-enable \
        imagick \
        amqp \
        redis

# Compile and install lockrun
RUN mkdir ./lockrun \
    && cd ./lockrun \
    && wget http://www.unixwiz.net/tools/lockrun.c \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && gcc lockrun.c -o lockrun \
    && cp lockrun /usr/bin/ \
    && chmod 755 /usr/bin/lockrun \
    && rm -R ./lockrun

# Copy crontab file into dir
ADD crontab /etc/cron.d/crontab

# Add cron entrypoint
ADD cron-entrypoint.sh /cron-entrypoint.sh

# Changing mode for crontab
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab -u www-data /etc/cron.d/crontab && chmod +x /cron-entrypoint.sh

# Install composer
RUN export COMPOSER_ALLOW_SUPERUSER=1 \
    && wget https://get.symfony.com/cli/installer -O - | bash \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && composer --version \
    && rm composer-setup.php

# Remove build dependencies
RUN apk del build-deps $PHPIZE_DEPS && rm -rf /var/cache/apk/*

# Setting default warkdir
WORKDIR /var/www/app

# Expose 9000 port for fpm
EXPOSE 9000

ENTRYPOINT [""]
