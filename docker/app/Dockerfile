FROM php:cli

WORKDIR /opt/invite-registration

RUN apt-get update -yq && \
    apt-get install -yq libfreetype6-dev libicu-dev librabbitmq-dev unzip wget zlib1g-dev

RUN docker-php-ext-install bcmath iconv intl opcache pdo_mysql zip

RUN pecl install amqp xdebug

RUN docker-php-ext-enable amqp xdebug

COPY install_composer.sh /usr/local/bin/install_composer.sh

RUN install_composer.sh

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000", "-t", "public/"]
