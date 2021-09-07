FROM composer:2.0 as phpbuild
ADD web /var/www/html
WORKDIR /var/www/html
RUN composer install


FROM node:13 as npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install && npm run production && rm -rf /var/www/html/node_modules


FROM php:7.3-apache
# enable rewrite for Laravel pretty URLs
RUN a2enmod rewrite
# change apache webroot from / to /public/
RUN sed -i s/"DocumentRoot \/var\/www\/html"/"DocumentRoot \/var\/www\/html\/public"/ /etc/apache2/sites-available/000-default.conf

RUN apt-get update \
    && apt-get install -y \
       libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install xml opcache pdo_mysql

COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod a+w -R /var/www/html/bootstrap/cache
RUN chmod a+w -R /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html
