#!/bin/bash

# PHP_VERSION=$(phpenv version-name)

# if [ $PHP_VERSION == '5.2' ]; then 
# 	CONF_NAME='nginx.52.conf'
# else
	CONF_NAME='nginx.conf'
# fi

apt-get install nginx
cp $CONF_NAME /etc/nginx/nginx.conf
echo "127.0.0.1 example.org" | sudo tee -a /etc/hosts
/etc/init.d/nginx restart
