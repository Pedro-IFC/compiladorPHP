FROM php:8.3-apache
COPY my-apache-config.conf /etc/apache2/conf-available/my-apache-config.conf
RUN a2enmod rewrite && a2enconf my-apache-config
COPY . /var/www/html/
#RUN chown -R www-data:www-data /var/www/html
EXPOSE 80