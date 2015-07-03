Transcription Desk for Citizen Science
===

This student project of summer 2015 is part of the Citizen Science course run by Thomas Koentges, found at https://github.com/ThomasK81/CitizenScienceCourse.
We aim to develop the tools necessary for a web application Transcription Desk utilising the Markdown markup language.

Project usage
---
One of the main goals of this project is to enable the web transcription desk in the use case of transcribing latin handwritings.

Dependencies
---
* You need either [Vagrant](https://www.vagrantup.com/) or [Docker](https://www.docker.com/) configured and running, as described in our [container/README.md](https://github.com/runjak/TranscriptionDesk/blob/master/container/README.md).

Deploy
---
* Copy ```src/config_example.php``` to ```src/config.php```, and fill in the authentication details for:
    * Your database, if you're using a different setup than what our container directory provides via the [```install.sh```](https://github.com/runjak/TranscriptionDesk/blob/master/container/install.sh) script.
    * Your Omeka API key and endpoint.

Reference project
---

Imagespecs! Github Koentges
