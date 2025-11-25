FROM php:8.3-fpm

ARG WWWGROUP=1000
ARG WWWUSER=1000

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        pcntl \
        intl \
        pdo_mysql \
        zip \
        gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

RUN if [ "${WWWGROUP}" != "33" ] || [ "${WWWUSER}" != "33" ]; then \
        groupadd -g ${WWWGROUP} laravel && \
        useradd -u ${WWWUSER} -ms /bin/bash -g laravel laravel && \
        chown -R ${WWWUSER}:${WWWGROUP} /var/www; \
    fi

CMD ["php-fpm"]

