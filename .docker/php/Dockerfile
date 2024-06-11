FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    wget \
    nano \
    libzip-dev \
    libexif-dev \
    libwebp-dev \
    libtiff-dev \
    libicu-dev \
    libmagickwand-dev \
    netcat-openbsd

RUN pecl install imagick && docker-php-ext-enable imagick

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd pdo pdo_mysql mbstring zip exif intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/stockpress

COPY ./backend/ /var/www/stockpress

COPY ./.docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./.docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN chown -R www-data:www-data /var/www/stockpress && \
    chmod -R 755 /var/www/stockpress/storage && \
    chmod -R 755 /var/www/stockpress/bootstrap/cache

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# COPY ./docker/php/php.ini /usr/local/etc/php/

EXPOSE 9000

CMD ["php-fpm"]