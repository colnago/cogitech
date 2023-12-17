FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpq-dev

RUN curl -sS https://getcomposer.org/installer | php && \
  mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install zip \
    pdo \
    pdo_mysql

WORKDIR /var/www/app