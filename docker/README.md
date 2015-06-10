What this is for:
===
Run a [Docker](https://www.docker.com/) container with a [LAMP](https://en.wikipedia.org/wiki/LAMP_(software_bundle)) setup inside it.

Setup instructions for this docker container:
===
1. Install docker. For support with that consult [docs.docker.com](https://docs.docker.com/installation/)
2. Execute ```make build```.
   This will fetch the ```ubuntu:latest``` image and install a LAMP stack on it.
   You'll have to interact with it once to set the password for the mysql server on it.
   Also the ```entrypoint.sh``` will be copied inside the container
   to simplify starting apache2 and mysqld inside it.
3. Execute ```make start``` to run the container.
   This will start apache2 and mysqld.
   Afterwards it will display the ip address used by your now running container.
   This command will leave you with an open shell for the inside of the container.
   Once you exit this shell, the container will also terminate.
