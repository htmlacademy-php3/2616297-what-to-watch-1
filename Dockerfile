FROM php:8.4-cli-alpine

RUN apk add --no-cache \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libxml2-dev \
        oniguruma-dev \
        nodejs \
        npm \
        mysql-client \
        linux-headers \
        $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        zip \
        gd \
        mbstring \
        xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN composer install --no-interaction --no-progress --optimize-autoloader

RUN npm install && npm run prod

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]