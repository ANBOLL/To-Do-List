FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
	git unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
	&& docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
	&& pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
