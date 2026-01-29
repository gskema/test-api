FROM php:8.5-cli

# Install system dependencies + SQLite + Redis extension
RUN apt-get update && apt-get install -y \
    git curl unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get install -y bash # for debug

WORKDIR /app

# Install Composer fresh every build
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copy project code (overridden by volume anyway)
COPY src/ .

COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

# Set bash default if exists
SHELL ["/bin/bash", "-c"]

# Expose PHP built-in server
EXPOSE 8000

# Start PHP built-in server with fresh Composer install
CMD ["sh", "-c", "php -S 0.0.0.0:8080 -t ./public"]
