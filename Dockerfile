# Base image with Apache + PHP
FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy project files into container
COPY . /var/www/html/

# Expose web port
EXPOSE 80
