## Project Plan ##
* all milestones require extended testing!

# phase one #

* Interface Mockup
* Database Mockup
    * Tack timestamps on all things where possible
    * Build with URNs in mind (prefixes are potentially helpful for SELECT, etc. later)
* Build Omeka service provider
    * Should use Omeka instance to query it for images and their metadata.
    * There will be collections with items in them.
    * Each item contains numerous images and some metadata.
    * Important metadata are:
        * Copyright
        * Public: Boolean
        * Featured: Boolean
    * Case dependency on metadata:
        * Public && Featured: Display even for not logged in users
        * ¬Public && Featured: Display only for logged in users
        * else: act as if it doesn't exist
* User Registration Process and Login plus Personal Page
    * maybe use github, facebook, google oauth process to avoid password storage

# First Milestone #
* working website mockup with registration

# phase two #

* figure out CiteURN process
    * For transcriptions extend URN from AOIs (Areas Of Interest)
      with something like username+timestamp(yyyy-mm-dd)+'.md'
* display file pictures in frontend, allow marking of areas of interest
* save areas of interest in backend and user profile

# Second Milestone #

* areas of interest can be marked, saved as CiteURN, can be saved in user profile

# phase three #

* add transcription desk and markdown view; strikethrough, allow special characters
* Latin a,b,c...Mufi for unicode characters
* save transcriptions, user motivation star system

# Third Milestone #

* transcription of areas of interest
* whole project progress bar
* user motivation stars

# phase four #

* bug fixing
* voting system

# Fourth Milestone #

* voting
* finished project
* well written project report

