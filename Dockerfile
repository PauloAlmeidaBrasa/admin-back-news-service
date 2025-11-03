FROM php:8.3-apache

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    libpq-dev libfreetype6-dev libjpeg62-turbo-dev libwebp-dev libxpm-dev \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# # Enable PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite headers
RUN a2enmod rewrite


# Copy project files
COPY . .

# Configure Apache
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Add startup script
RUN echo '#!/bin/bash\n\
set -e\n\
cd /var/www/html\n\
\n\
if [ ! -d "vendor" ]; then\n\
  echo "🧩 Installing Composer dependencies..."\n\
  composer install --ignore-platform-reqs\n\
fi\n\
\n\
echo "🧼 Clearing cache..."\n\
php artisan optimize:clear || true\n\
\n\
if [ -f "artisan" ]; then\n\
  echo "📜 Publishing Swagger assets..."\n\
  php artisan vendor:publish --provider="L5Swagger\\\\L5SwaggerServiceProvider" --tag=config --force || true\n\
  php artisan vendor:publish --provider="L5Swagger\\\\L5SwaggerServiceProvider" --tag=views --force || true\n\
  php artisan l5-swagger:generate || true\n\
fi\n\
\n\'

EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
