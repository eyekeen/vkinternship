FROM php:8.1-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install any extensions you need
RUN apt-get update \
  && apt-get install -y libpq-dev libzip-dev git zip unzip \
  && docker-php-ext-install pdo pdo_mysql zip \
  && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the source code in /www into the container at /var/www/html
COPY . /var/www/html

# Copy the entrypoint script into the container
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Use the entrypoint script as the entrypoint
ENTRYPOINT ["entrypoint.sh"]