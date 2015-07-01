docker:
	docker build --tag="transcription_desk" .

start:
	docker run -a STDIN -a STDOUT -a STDERR -i -t --volume=`pwd`/../src:/var/www/html transcription_desk

vagrant:
	vagrant box add https://atlas.hashicorp.com/ubuntu/boxes/vivid64
	vagrant up
