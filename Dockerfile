FROM php:8.2-apache

# Cài đặt extension và chứng chỉ bảo mật cho Aiven
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Cài thư viện và xóa bộ đệm cũ
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN rm -rf bootstrap/cache/*.php

# Cấp quyền ghi cho các thư mục quan trọng
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# ÉP APACHE NHẬN ĐƯỜNG DẪN LARAVEL BẰNG VIRTUALHOST
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Kích hoạt module điều hướng và headers
RUN a2enmod rewrite headers

EXPOSE 80