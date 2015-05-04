#!/bin/bash

# PHP_VERSION=$(phpenv version-name)

# if [ $PHP_VERSION == '5.2' ]; then 
# 	CONF_NAME='./ci/nginx.52.conf'
# else
	CONF_NAME='./ci/nginx.conf'
# fi

apt-get install nginx
cp $CONF_NAME /etc/nginx/nginx.conf
echo "127.0.0.1 example.org" >> /etc/hosts
/etc/init.d/nginx restart
