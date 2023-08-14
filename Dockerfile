
#
# Pull base image
# ---------------
FROM php:8.2.8-cli-bookworm
#
# Labels
# ------
LABEL "provider"="PHP" \
  "issues"="https://github.com/docker-library/php/issues"

#
ARG ORACLE_LATEST=12.1

# Use second ENV so that variable get substituted
ENV TZ=America/Manaus \
LC_ALL=C.UTF-8 \
LANG=pt_BR.UTF-8 \
LANGUAGE=pt_BR:pt:pt_PT:en \
ORACLE_LATEST=${ORACLE_LATEST} \
ORACLE_BASE=/usr/lib/oracle \
ORACLE_HOME=/usr/lib/oracle/${ORACLE_LATEST}/client64 \
NLS_TERRITORY=BRAZIL \
NLS_LANG="BRAZILIAN PORTUGUESE_BRAZIL.UTF8" \
NLS_LANGUAGE="BRAZILIAN PORTUGUESE" \
NLS_CHARACTERSET=UTF8 \
NLS_NCHAR_CHARACTERSET=AL32UTF8 \
NLS_CURRENCY="R$" \
NLS_NUMERIC_CHARACTERS=".," \
NLS_SORT=WEST_EUROPEAN_AI \
NLS_COMP=BINARY \
NLS_DATE_FORMAT="RRRR-MM-DD HH24:MI:SS" \
NLS_TIMESTAMP_FORMAT="RRRR-MM-DD HH24:MI:SS.FF"

# Config PHP
# ------------------------------
# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Override with custom mysql settings
# COPY config/mysql.ini $PHP_INI_DIR/conf.d/
# COPY config/pdo_mysql.ini $PHP_INI_DIR/conf.d/
# ------------------------------
# pecl install --force --soft --alldeps redis --enable-redis-igbinary=no --enable-redis-lzf=no --enable-redis-zstd=no ; \
#
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
libaio1 \
libbz2-dev \
libfreetype6-dev \
libicu-dev \
libjpeg-dev \
libpng-dev \
libpq-dev \
libzip-dev \
git git-lfs locales alien ; \
localedef -i $(echo ${LANG} | cut -d. -f1) -c -f $(echo ${LANG} | cut -d. -f2) -A /usr/share/locale/locale.alias ${LANG} ; \
locale-gen ${LANG} ; \
date ; \
\
git clone https://github.com/rodrigocabral78/instantclient${ORACLE_LATEST}.git /tmp/instantclient${ORACLE_LATEST} ; \
CLIENTS='basiclite devel sqlplus tools' ; \
for CLI in ${CLIENTS}; do echo ${CLI}; alien --install --scripts /tmp/instantclient${ORACLE_LATEST}/oracle-instantclient${ORACLE_LATEST}-${CLI}*rpm ; done ; \
apt-get install -f -y --no-install-recommends ; \
export TNS_ADMIN=${ORACLE_HOME}/lib/network/admin ; \
export LD_LIBRARY_PATH=${LD_LIBRARY_PATH}:${ORACLE_HOME}/lib ; \
export C_INCLUDE_PATH=${C_INCLUDE_PATH}:/usr/include/oracle/${ORACLE_LATEST}/client64 ; \
mkdir -p ${TNS_ADMIN} ; \
export PATH=${ORACLE_HOME}/bin:$PATH ; echo $PATH ; \
echo ${ORACLE_HOME}"/lib" | tee /etc/ld.so.conf.d/oracle.conf ; ldconfig ; \
\
docker-php-source extract ; \
docker-php-ext-configure bz2 --with-bz2 ; \
docker-php-ext-configure gd --with-freetype --with-jpeg ; \
docker-php-ext-configure oci8 --with-oci8=instantclient ; \
docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,${ORACLE_HOME}/lib ; \
docker-php-ext-install -j$(nproc) bcmath bz2 calendar exif gd intl mysqli oci8 pdo_mysql pdo_oci pdo_pgsql pgsql zip ; \
\
pecl channel-update pecl.php.net ; \
pecl install --force --soft --onlyreqdeps redis --enable-redis-igbinary=yes --enable-redis-lzf=no --enable-redis-zstd=no ; \
docker-php-ext-enable redis ; \
pecl clear-cache ; \
docker-php-source delete ; \
unset C_INCLUDE_PATH LD_LIBRARY_PATH ; \
\
apt-get purge -y locales alien software-properties-common oracle-instantclient${ORACLE_LATEST}-devel ; apt-get autoclean ; apt-get autoremove -y ; rm -rf /var/lib/apt/lists/* ; rm -rf /usr/lib/python3 \
; apt-get update -y ; apt-get upgrade -yq --no-install-recommends ; apt-get autoclean ; apt-get autoremove -y ; rm -rf /var/lib/apt/lists/*


# COPY ./api /var/www/app

# WORKDIR /var/www/app

# EXPOSE 8001

# CMD [ "php","-c $PHP_INI_DIR/php.ini-production", "-welsS 0.0.0.0:8001", "-t public/", "public/index.php" ]
