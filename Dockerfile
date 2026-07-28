FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    libonig-dev \
    libzip-dev \
    zip \
    curl \
    libpq-dev

RUN docker-php-ext-install pdo_sqlite pdo_mysql mbstring zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/database

# 复制我们写好的 Apache 配置文件并启用
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

EXPOSE 80
