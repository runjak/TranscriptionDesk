What this is for:
===
This directory currently provides two ways to have our development setup in a container:
* Run a [Vagrant](https://www.vagrantup.com/) machine with a [LAMP](https://en.wikipedia.org/wiki/LAMP_(software_bundle)) setup inside it.
* Run a [Docker](https://www.docker.com/) container with the same setup.

Setup instructions for Vagrant:
===
1. Install vagrant. For support consult [www.vagrantup.com](https://www.vagrantup.com/)
2. Execute ```make vagrant``` or manually execute the commands
    
    ```
    vagrant box add https://atlas.hashicorp.com/ubuntu/boxes/vivid64
    vagrant up
    ```
3. Enjoy your website at ```http://localhost:8080```

Setup instructions for Docker:
===
1. Install docker. For support with that consult [docs.docker.com](https://docs.docker.com/installation/)
2. Execute ```make docker```.
   This will fetch the ```ubuntu:latest``` image and install a LAMP stack on it.
   You'll have to interact with it once to set the password for the mysql server on it.
   Also the ```entrypoint.sh``` will be copied inside the container
   to simplify starting apache2 and mysqld inside it.
3. Execute ```make start``` to run the container.
   This will start apache2 and mysqld.
   Afterwards it will display the ip address used by your now running container.
   This command will leave you with an open shell for the inside of the container.
   Once you exit this shell, the container will also terminate.
