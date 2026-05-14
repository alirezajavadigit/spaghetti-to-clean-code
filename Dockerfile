FROM php:8.3-apache
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        sqlite3 \
        libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*
RUN a2enmod rewrite
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
    zip
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf
COPY . /var/www/html
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer
RUN composer install
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]