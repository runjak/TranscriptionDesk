#For this project, we can use docker to optain a LAMP container.
#See https://docs.docker.com/reference/builder/ for reference.
FROM ubuntu:latest
MAINTAINER Jakob Runge <mam09crm@studserv.uni-leipzig.de>
LABEL Description="Dockerfile for https://github.com/runjak/TranscriptionDesk" Version="0.0.1"
RUN apt-get install -y apache2-mpm-prefork libapache2-mod-php5 php5 php5-mysql
RUN apt-get install -y mysql-server
RUN rm /var/www/html/index.html
COPY create.sql /root/
COPY mysqlSetup.sh /root/
RUN /root/mysqlSetup.sh
EXPOSE 80
COPY entrypoint.sh /
ENTRYPOINT ["bash","-c","/entrypoint.sh"]
