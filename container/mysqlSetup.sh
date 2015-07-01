#!/bin/bash
#This script will be executed inside a container.
debconf-set-selections <<< 'mysql-server mysql-server/root_password password 1234'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 1234'
apt-get install -y mysql-server
service mysql start
echo "Creating database: TranscriptionDesk"
echo "CREATE DATABASE TranscriptionDesk CHARACTER SET utf8;"|mysql -uroot -p1234
echo "Creating tables from create.sql"
mysql -uroot -p1234 TranscriptionDesk < $1
