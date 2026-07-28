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

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -s 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -s 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 关键：开启 AllowOverride 以支持 Laravel 的 .htaccess 路由重写
RUN echo '<Directory /var/www/html/public>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>' >> /etc/apache2/apache2.conf

RUN a2enmod rewrite

EXPOSE 80
