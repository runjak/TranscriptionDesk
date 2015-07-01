#For this project, we can use docker to optain a LAMP container.
#See https://docs.docker.com/reference/builder/ for reference.
FROM ubuntu:latest
MAINTAINER Jakob Runge <mam09crm@studserv.uni-leipzig.de>
LABEL Description="Dockerfile for https://github.com/runjak/TranscriptionDesk" Version="0.0.1"
COPY install.sh /root/
COPY create.sql /root/
RUN /root/install.sh /root/create.sql
RUN rm /var/www/html/index.html
EXPOSE 80
COPY entrypoint.sh /
ENTRYPOINT ["bash","-c","/entrypoint.sh"]
