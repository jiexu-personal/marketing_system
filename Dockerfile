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

# 1. 删掉 Apache 默认自带的所有乱七八糟的虚拟主机配置
RUN rm -f /etc/apache2/sites-enabled/* /etc/apache2/sites-available/*

# 2. 把我们自己的配置文件复制过去并启用
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/

RUN a2enmod rewrite

EXPOSE 80
