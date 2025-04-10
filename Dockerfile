# 使用官方的 PHP 8.2 FPM 基礎映像
FROM php:8.2-fpm

# 設定工作目錄
WORKDIR /var/www

# 安裝系統套件和 PHP 擴充套件的相依性
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite mbstring zip

# 複製專案檔案到容器內
COPY . .

# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 安裝 Laravel 相依套件
RUN composer install --no-dev --optimize-autoloader

# 設定權限
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 暴露埠口
EXPOSE 9000

# 啟動 PHP-FPM 伺服器
CMD ["php-fpm"]
