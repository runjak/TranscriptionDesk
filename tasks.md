## Tasks - to be updated, look ProjectPlan##

This list of task needs discussion in the group,
and may not be complete.
An example of what a milestone with some issues in it may look like consider [[1](https://github.com/runjak/turnt-dubstep/milestones),[2](https://github.com/sndcomp/website/milestones?state=closed)].

# List of tasks #

1. Define data that needs to be stored for…
    * users
        * username, unique; useable for CITE URNs
        * password hash+salt
        * freetext Markdown description
    * list of scans
        * URN?
    * list of rectangular parts of scans
        * CITE URN
        * comments?
        * userId
        * datum
        * list of rectangles
        * How are areas described?
        * What will the CITE URNs look like?
        * What metadata/additional data will/should be stored?
    * transcriptions for rectangular/polygon parts
        * What data needs storage?
        * How will CITE URNs look for this?
    * General configuration?
    * Develop MySQl schema for this
        * ER-Diagram
        * SQL code
2. Create a mock up for a possible TranscriptionDesk interface.
    * How can we present the website so that a user can transcribe something?
    * How can we present the selection of parts of scans?
    * Perhaps some general style ideas/decisions
        * Is there a framework (bootstrap?) that we can use?
3. Define building blocks for our project report
    * Are there parts that can already be filled from other tasks?
    * What format to use? LaTeX? Possibly create template/start of it?
4. Create some diagrams for system architecture/data flows?
