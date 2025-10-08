FROM php:8.2-cli

WORKDIR /var/www/html
RUN chmod -R 777 /var/www/html


RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/html
# RUN git config --global --add safe.directory /var/www/html

RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]







# FROM php:8.2-cli

# WORKDIR /var/www/html

# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip \
#     libzip-dev \
#     libicu-dev \
#     libpq-dev \
#     libfreetype6-dev \
#     libjpeg62-turbo-dev \
#     libwebp-dev \
#     libxpm-dev

# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

# RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# COPY . .

# RUN composer install --ignore-platform-reqs

# #RUN php artisan key:generate

# RUN chown -R www-data:www-data /var/www/html

# EXPOSE 8000

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]