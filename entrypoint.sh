#!/bin/bash

# Установка зависимостей Composer
composer install

# Запуск Apache в фоновом режиме
exec apache2-foreground