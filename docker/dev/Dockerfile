FROM composer:2.5 as build_stage

LABEL maintainer="Fibo Host Dev"
COPY . /src
ADD .env.example /src/.env

WORKDIR /src
RUN composer install --no-ansi --no-interaction --no-progress --no-scripts --optimize-autoloader --ignore-platform-reqs
RUN php artisan key:generate

FROM php:8.3-apache

# 1. Install development packages and clean up apt cache.
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    g++ \
    git \
    libzip-dev \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libpng-dev \
    libreadline-dev \
    sudo \
    unzip \
    zip \
    nano \
    redis \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP Packages
# Development packages not intended for production
RUN apt-get update && apt-get install -y awscli && rm -rf /var/lib/apt/lists/*

#2.1 CONFIGURE GD 
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg 
RUN docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql

#2.2
RUN docker-php-ext-install \
    bcmath \
    bz2 \
    calendar \
    iconv \
    intl \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    zip \ 
    gd \
    exif

# 3. Composer.
COPY --from=composer:2.5.8 /usr/bin/composer /usr/bin/composer

# copy apache server configration 
COPY ./docker/apache/default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=build_stage /src /var/www/html
RUN chown -R www-data:www-data /var/www

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# set file permissions
RUN chmod -R 775 storage && chmod -R 775 bootstrap/cache

# Copy entrypoint script & set execution permissions
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set the entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
