FROM php:8.1-apache

# Enable Apache modules
RUN a2enmod rewrite
# Install any extensions you need
RUN apt-get update \
  && apt-get install -y --no-install-recommends libpq-dev libzip-dev git zip unzip \
  && docker-php-ext-install mysqli pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN export COMPOSER_ALLOW_SUPERUSER=1


# Remove Cache
RUN rm -rf /var/cache/apk/*
# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the source code in /www into the container at /var/www/html
COPY . /var/www/html

# Change current user to www
USER root

RUN composer install --no-dev --optimize-autoloader --no-interaction