What this is for:
===
We use [Vagrant](https://www.vagrantup.com/) to provide the development setup in a container.
The Vagrantfile creates a [LAMP](https://en.wikipedia.org/wiki/LAMP_(software_bundle)) setup.

Setup instructions for Vagrant:
===
1. Install vagrant. For support consult [www.vagrantup.com](https://www.vagrantup.com/).
2. Execute the commands
    
    ```
    vagrant box add https://atlas.hashicorp.com/ubuntu/boxes/vivid64
    vagrant up
    ```
3. Enjoy your website at ```http://localhost:8080```
4. View your apache2 logs in the ```logs``` directory.

Some vagrant commands:
===
* Use ```vagrant suspend``` and ```vagrant resume``` for fast stop&start with your container.
* If you want to completely restart the container use ```vagrant halt``` and ```vagrant up```.
* To get a shell inside the container use ```vagrant ssh```.
* To rebuild your container use ```vagrant destroy; vagrant up```.
