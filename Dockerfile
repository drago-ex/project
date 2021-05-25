# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# run commands
RUN apt-get update && apt-get upgrade -y && a2enmod rewrite && apt-get install -y libaio1 && docker-php-ext-install mysqli

# the ports
EXPOSE 80
