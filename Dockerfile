# Use the official PHP image as the base image
# Pinned to the 8.2 line rather than the 8.2.0 patch: 8.2.0 shipped in December 2022 and
# has accumulated security fixes since. Laravel 11 requires ^8.2.
#
# Moving to 8.3 (which CI also tests) is worth doing separately — it needs an actual image
# build to confirm the swoole pecl extension still compiles.
FROM php:8.2-cli-bullseye as base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libicu-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    npm \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install PHP extensions
RUN pecl install swoole
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl
RUN docker-php-ext-install exif pcntl bcmath gd intl
RUN docker-php-ext-enable swoole
RUN docker-php-ext-configure intl

# Configure PHP
RUN sed -i -e "s/upload_max_filesize = .*/upload_max_filesize = 1G/g" \
    -e "s/post_max_size = .*/post_max_size = 1G/g" \
    -e "s/memory_limit = .*/memory_limit = 1G/g" \
    /usr/local/etc/php/php.ini-production \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Get latest Composer and install
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

# create new user
RUN useradd -ms /bin/bash swoole

# Set working directory
WORKDIR /home/swoole

FROM base as development

RUN npm install chokidar
RUN npm i

# Dependencies are installed before the application is copied so that Docker can cache
# this layer. At this point there is no artisan file, so composer's post-autoload-dump
# hooks (package:discover, filament:upgrade) cannot run yet — hence --no-scripts.
COPY --chown=swoole:swoole composer.json ./
COPY --chown=swoole:swoole composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY --chown=swoole:swoole ./ ./

# Now that artisan exists, generate the autoloader and let the hooks run. This is what
# rebuilds bootstrap/cache/packages.php and publishes Filament's CSS and JS into public/,
# which is why neither is committed to the repository.
RUN composer dump-autoload --optimize --no-interaction

USER swoole
CMD [ "php", "artisan", "octane:start",  "--host=0.0.0.0", "--port=80" ]
