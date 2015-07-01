#!/bin/bash
#This script will be executed inside the docker container.
service mysql start
echo "Creating database: TranscriptionDesk"
echo "CREATE DATABASE TranscriptionDesk CHARACTER SET utf8;"|mysql -uroot -p
echo "Creating tables from create.sql"
mysql -uroot -p TranscriptionDesk < $1
