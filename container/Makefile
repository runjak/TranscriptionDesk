build:
	docker build --tag="transcription_desk" .

start:
	docker run -a STDIN -a STDOUT -a STDERR -i -t --volume=`pwd`/../src:/var/www/html transcription_desk
