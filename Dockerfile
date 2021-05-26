# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# run commands
RUN apt-get update && apt-get upgrade -y && a2enmod rewrite && apt-get install -y libaio1 && docker-php-ext-install mysqli
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# php configuration
ADD /php.ini /etc/php/8.0/apache2/php.ini

# the ports
EXPOSE 80
