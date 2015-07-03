#!/bin/bash
# This script will be executed inside a container.
# $1 is expected to be the create.sql file.
apt-get update
apt-get dist-upgrade
apt-get install -y apache2-mpm-prefork libapache2-mod-php5 php5 php5-mysql php5-curl
# Setting up MySQL:
debconf-set-selections <<< 'mysql-server mysql-server/root_password password 1234'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 1234'
apt-get install -y mysql-server
service mysql start
echo "Creating database: TranscriptionDesk"
echo "CREATE DATABASE TranscriptionDesk CHARACTER SET utf8;"|mysql -uroot -p1234
echo "Creating tables from create.sql"
mysql -uroot -p1234 TranscriptionDesk < $1
service mysql stop
# Configuring apache2 for mod_rewrite and AllowOverride:
a2enmod rewrite
echo -e "<Directory \"/var/www/html\">\nAllowOverride All\n</Directory>" >> /etc/apache2/sites-enabled/000-default.conf
