FROM php:8.2-cli

# 安装 SQLite 扩展和依赖
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    libonig-dev \
    libzip-dev \
    zip \
    curl \
    libpq-dev

RUN docker-php-ext-install pdo_sqlite pdo_mysql mbstring zip

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html
COPY . .

# 安装依赖
RUN composer install --no-dev --optimize-autoloader

# 给 storage 和 database 文件夹赋权（必须项）
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/database

# 暴露端口
EXPOSE 80

# 运行启动脚本
CMD ["/var/www/html/start.sh"]
