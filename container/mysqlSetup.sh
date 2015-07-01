#!/bin/bash
#This script will be executed inside the docker container.
service mysql start
echo "Setting MySql password: 1234"
echo "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('1234');"|mysql -uroot
echo "Creating database: TranscriptionDesk"
echo "CREATE DATABASE TranscriptionDesk CHARACTER SET utf8;"|mysql -uroot -p1234
echo "Creating tables from create.sql"
mysql -uroot -p1234 TranscriptionDesk < create.sql
