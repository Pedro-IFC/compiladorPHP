FROM php:8.3-apache
COPY my-apache-config.conf /etc/apache2/conf-available/my-apache-config.conf
RUN a2enmod rewrite && a2enconf my-apache-config
RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY . /var/www/html/
#RUN chown -R www-data:www-data /var/www/html
EXPOSE 80