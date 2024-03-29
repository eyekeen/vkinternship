FROM php:8.1-apache

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y curl git zip \
  && docker-php-ext-install pdo mysqli pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer