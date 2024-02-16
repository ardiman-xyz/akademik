FROM php:7.4

# Install dependensi dan ekstensi PHP yang dibutuhkan oleh Laravel
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

# Set working directory di dalam container
WORKDIR /var/www/html

# Copy semua file proyek Laravel ke dalam container
COPY . .
