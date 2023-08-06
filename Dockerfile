
#
# Pull base image
# ---------------
FROM php:8.2.8-cli-bookworm
#
# Labels
# ------
LABEL "provider"="PHP" \
  "issues"="https://github.com/docker-library/php/issues"

# Use second ENV so that variable get substituted
ENV TZ=America/Manaus \
LC_ALL=C.UTF-8 \
LANG=pt_BR.UTF-8 \
LANGUAGE=pt_BR:pt:pt_PT:en \
DEBIAN_FRONTEND=noninteractive

# Config PHP
# ------------------------------
RUN set -xe ; \
echo ${TZ} | tee /etc/timezone ; \
cp -rf /usr/share/zoneinfo/${TZ} /etc/localtime ; \
\
{ echo "[Date]"; echo "; Defines the default timezone used by the date functions"; echo "; http://php.net/date.timezone"; echo "; https://www.php.net/manual/pt_BR/timezones.america.php"; echo "; priority=20"; echo "date.timezone = ${TZ}"; } | tee ${PHP_INI_DIR}/conf.d/date.ini ; \
cp ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini ; \
\
curl -sSi https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer ; composer --version ; \
\
apt-get update -y ; apt-get upgrade -yq --no-install-recommends ; \
apt-get install -y --no-install-recommends \
libicu-dev \
libfreetype6-dev \
libjpeg-dev \
libpng-dev \
libzip-dev \
libbz2-dev \
libicu-dev \
git git-lfs locales ; \
localedef -i $(echo ${LANG} | cut -d. -f1) -c -f $(echo ${LANG} | cut -d. -f2) -A /usr/share/locale/locale.alias ${LANG} ; \
locale-gen ${LANG} ; \
date ; \
\
docker-php-source extract ; \
docker-php-ext-configure bz2 --with-bz2 ; \
docker-php-ext-configure gd --with-freetype --with-jpeg ; \
docker-php-ext-configure intl ; \
docker-php-ext-configure zip ; \
docker-php-ext-install -j$(nproc) bcmath bz2 calendar exif gd intl zip ; \
docker-php-ext-enable bcmath bz2 calendar exif gd intl zip ; \
pecl install redis ; \
pecl install swoole ; \
pecl install xdebug ;\
docker-php-ext-enable redis swoole xdebug ; \
pecl clear-cache ; \
docker-php-source delete ; \
\
apt-get purge -y locales software-properties-common ; apt-get autoclean ; apt-get autoremove -y ; rm -rf /var/lib/apt/lists/* ; rm -rf /usr/lib/python3 \
; apt-get update -y ; apt-get upgrade -yq --no-install-recommends ; apt-get autoclean ; apt-get autoremove -y ; rm -rf /var/lib/apt/lists/*



# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Override with custom mysql settings
# COPY config/mysql.ini $PHP_INI_DIR/conf.d/
# COPY config/pdo_mysql.ini $PHP_INI_DIR/conf.d/

# COPY ./api /var/www/app

# WORKDIR /var/www/app

# EXPOSE 8001

# CMD [ "php","-c $PHP_INI_DIR/php.ini-production", "-welsS 0.0.0.0:8001", "-t public/", "public/index.php" ]
