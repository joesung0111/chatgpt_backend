FROM php:8.2-fpm

# 安裝必要的套件
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite mbstring

# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www

# 複製專案檔案
COPY . .

# 安裝 Laravel 相依套件
RUN composer install --no-dev --optimize-autoloader

# 設定權限
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 暴露埠口
EXPOSE 8000

# 啟動 Laravel 內建伺服器
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
