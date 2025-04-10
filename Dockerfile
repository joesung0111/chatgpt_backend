FROM php:8.2-fpm

# 安裝系統套件與 PHP 擴充
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    sqlite3 \
    libsqlite3-dev \
    git \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# 安裝 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www

# 複製專案檔案
COPY . .

# 安裝 Laravel 相依
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 權限設定
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# 暴露 port
EXPOSE 8000

# 啟動 Laravel 伺服器
CMD php artisan serve --host=0.0.0.0 --port=8000
