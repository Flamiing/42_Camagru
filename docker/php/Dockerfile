FROM php:8.3.7-apache

# Declare the variables:
ARG SSMTP_ROOT
ARG SSMTP_MAILHUB
ARG SSMTP_AUTH_USER
ARG SSMTP_AUTH_PASS
ARG SSMTP_AUTH_METHOD
ARG SSMTP_USE_TLS
ARG SSMTP_USE_STARTTLS
ARG SSMTP_HOSTNAME
ARG SSMTP_FROM_LINE_OVERRIDE
ARG MY_USER_ID
ARG MY_GROUP_ID

# Set the variables as environment variables:
ENV SSMTP_ROOT=$SSMTP_ROOT
ENV SSMTP_MAILHUB=$SSMTP_MAILHUB
ENV SSMTP_AUTH_USER=$SSMTP_AUTH_USER
ENV SSMTP_AUTH_PASS=$SSMTP_AUTH_PASS
ENV SSMTP_AUTH_METHOD=$SSMTP_AUTH_METHOD
ENV SSMTP_USE_TLS=$SSMTP_USE_TLS
ENV SSMTP_USE_STARTTLS=$SSMTP_USE_STARTTLS
ENV SSMTP_HOSTNAME=$SSMTP_HOSTNAME
ENV SSMTP_FROM_LINE_OVERRIDE=$SSMTP_FROM_LINE_OVERRIDE
ENV MY_USER_ID=$MY_USER_ID
ENV MY_GROUP_ID=$MY_GROUP_ID

RUN apt-get update -y && apt-get upgrade -y

RUN apt-get install -y ssmtp

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN docker-php-ext-install gd pdo_mysql

RUN a2enmod rewrite

COPY conf/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY conf/php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./tools/setup.sh /usr/local/bin

RUN chmod +x /usr/local/bin/setup.sh

USER root

RUN echo "root=${SSMTP_ROOT}\n\
mailhub=${SSMTP_MAILHUB}\n\
AuthUser=${SSMTP_AUTH_USER}\n\
AuthPass=${SSMTP_AUTH_PASS}\n\
AuthMethod=${SSMTP_AUTH_METHOD}\n\
UseTLS=${SSMTP_USE_TLS}\n\
UseSTARTTLS=${SSMTP_USE_STARTTLS}\n\
hostname=${SSMTP_HOSTNAME}\n\
FromLineOverride=${SSMTP_FROM_LINE_OVERRIDE}" > /etc/ssmtp/ssmtp.conf

RUN echo "user:x:${MY_USER_ID}:${MY_GROUP_ID}:user:/home/user:/bin/bash" >> /etc/passwd

USER ${MY_USER_ID}:${MY_GROUP_ID}

ENTRYPOINT ["bash", "/usr/local/bin/setup.sh"]
