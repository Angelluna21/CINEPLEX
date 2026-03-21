FROM php:8.4-apache

# Instalar dependencias del sistema requeridas por Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    git \
    nodejs \
    npm

# Configurar e instalar extensiones de PHP (MySQL, PostgreSQL, GD, etc)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd xml zip

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Apuntar el DocumentRoot de Apache a la carpeta "public" de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto al contenedor
COPY . .

# Instalar dependencias de PHP para producción
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Instalar Node.js y compilar recursos de Vite/Mix
RUN npm install
RUN npm run build

# Dar permisos necesarios a las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Cambiar dinámicamente el puerto de Apache al puerto proporcionado por Render ($PORT) y arrancar
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
