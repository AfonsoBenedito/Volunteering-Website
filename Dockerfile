FROM php:7.4-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install PHP extensions (apache2-utils with htpasswd is already in the base image)
RUN docker-php-ext-install mysqli

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html/

# Ensure upload directories exist
RUN mkdir -p /var/www/html/assets/FotosVoluntario \
    && mkdir -p /var/www/html/assets/FotosInstituicao

# Generate admin panel credentials (admin / admin)
RUN htpasswd -cb /var/www/html/admin/.htpasswd admin admin

# Fix ownership
RUN chown -R www-data:www-data /var/www/html/
