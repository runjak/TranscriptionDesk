Transcription Desk for Citizen Science
===

This student project of summer 2015 is part of the Citizen Science course run by Thomas Koentges, found at https://github.com/ThomasK81/CitizenScienceCourse.
We aim to develop the tools necessary for a web application Transcription Desk utilising the Markdown markup language.

Project usage
---
One of the main goals of this project is to enable the web transcription desk in the use case of transcribing latin handwritings.

Dependencies
---
* You need [Vagrant](https://www.vagrantup.com/) configured and running, as described in our [container/README.md](https://github.com/runjak/TranscriptionDesk/blob/master/container/README.md).
* If Vagrant is not an option for you, the ```Vagrantfile``` in the ```container``` directory has setup instructions you may want to look at.

Deploy
---
* Copy ```src/config_example.php``` to ```src/config.php```, and fill in the authentication details for:
    * Your database, if you're using a different setup than what our container directory provides.
      Use the [```create.sql```](https://github.com/runjak/TranscriptionDesk/blob/master/container/create.sql) script.
    * Your Omeka API key and endpoint.
    * Your authentication providers API keys.
* Rename ```src/auth/_htaccess``` to ```src/auth/.htaccess```.
    * If you're not running Vagrant you may need to adjust the ```RewriteBase``` clause in your ```src/auth/.htaccess```.

Reference project
---

Imagespecs! Github Koentges
