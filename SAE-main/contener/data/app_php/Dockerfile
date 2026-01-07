# syntax=docker/dockerfile:1.4
ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-apache as builder

# 1. Installation des dépendances système (bibliothèques nécessaires aux extensions)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libsqlite3-dev \
    sqlite3 \
    unzip \
    vim \
    && rm -rf /var/lib/apt/lists/*

# 2. Installation et activation des extensions PHP
# Cette commande compile les extensions spécifiquement pour votre version de PHP
RUN docker-php-ext-install \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    pdo_sqlite \
    curl \
    gd \
    opcache

# 3. Installation de Composer (méthode simplifiée)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Configuration Apache
# Assurez-vous que les fichiers sont bien dans le dossier 'app_php/data/' sur votre PC
ADD data/ci.conf data/default.conf /etc/apache2/sites-available/
ADD data/ports.conf /etc/apache2/

RUN a2dissite 000-default.conf && \
    a2ensite ci.conf && \
    a2ensite default.conf && \
    a2enmod rewrite

# 5. Configuration PHP (Utilise le mode développement par défaut)
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Pas besoin de copier manuellement les .so, docker-php-ext-install s'en occupe
CMD ["apache2-foreground"]