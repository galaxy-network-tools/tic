FROM centos:7

LABEL maintainer="worp@worp.io"

USER root

WORKDIR /var/www/tic

COPY docker/etc/yum.repos.d/nginx.repo /etc/yum.repos.d/nginx.repo

RUN yum -y install epel-release \
    && yum -y install \
        nginx-1.16.1 \
    # Any other packages we might like
    # Remove the yum cache at the end as it's useless storage waste
    && yum clean all

# Add any additional repositories we might like
RUN yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm \
    && yum -y install yum-utils \
    && yum-config-manager --enable remi-php72 \
    && yum -y install \
        php \
        php-fpm \
        php-cli \
        php-mysql \
    && yum clean all \
    # Create xdebug log
    && mkdir /var/log/xdebug \
    && touch /var/log/xdebug/xdebug.log

RUN yum install -y \
    vim

RUN mkdir -p /run/php-fpm

COPY docker/etc /etc
COPY docker/start.sh /start.sh

COPY src /var/www/tic

ENTRYPOINT ["/bin/bash", "-c"]
CMD ["/start.sh"]
