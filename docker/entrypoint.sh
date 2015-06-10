#!/bin/bash
#This script will be executed inside the docker container.
service mysql start
service apache2 start
echo "Network information of this container:"
ip addr show dev eth0 scope global
#We open a bash to keep the container running
#and make it possible to play with it:
bash
