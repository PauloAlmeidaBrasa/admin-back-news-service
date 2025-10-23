FROM php:8.3-cli

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    libpq-dev libfreetype6-dev libjpeg62-turbo-dev libwebp-dev libxpm-dev \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

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
\n\
echo "🚀 Starting Laravel..."\n\
php artisan serve --host=0.0.0.0 --port=8000\n' > /start.sh \
  && chmod +x /start.sh

EXPOSE 8000

CMD ["/bin/bash", "/start.sh"]
