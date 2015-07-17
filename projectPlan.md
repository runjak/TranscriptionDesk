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
* areas of interest
    * display file pictures in frontend
    * allow marking of areas of interest
    * add a category to area of interest, fixed number and type of categories
    * save them in backend and user profile
    * allow voting of areas of interest
    * implement progress bar
* transcription field
    * implement input of mufi characters in markdown
    * [MUFI Symbole](http://folk.uib.no/hnooh/mufi/specs/MUFI-Alphabetic-3-0.pdf)

* improve profile page

# Second Milestone #

* areas of interest can be marked, saved as CiteURN, can be voted on, can be saved in user profile
* working MUFI input system, live rendering of markdown

# phase three #

* add transcription desk and markdown view; strikethrough, allow special characters
* Latin a,b,c...Mufi for unicode characters
* save transcriptions, user motivation star system
    * save markdown in github

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

