-- This whole script shall run as a single transaction:
SET AUTOCOMMIT=0;
SET FOREIGN_KEY_CHECKS=0;
-- Making sure to drop tables if necessary:
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `transcriptions`;
DROP TABLE IF EXISTS `transcriptionCompleteness`;
DROP TABLE IF EXISTS `areasOfInterest`;
DROP TABLE IF EXISTS `aoiCompletenes`;
DROP TABLE IF EXISTS `scans`;
DROP TABLE IF EXISTS `scanCompleteness`;
DROP TABLE IF EXISTS `omekaItems`;
-- Creating tables from scratch:
-- Table for user data:
CREATE TABLE users (
    userId SERIAL,
    authenticationMethod VARCHAR(1024) NOT NULL,
    displayName VARCHAR(255) NOT NULL,
    lastLogin TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    tasksCompleted INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for transcription specific data:
CREATE TABLE transcriptions (
    urn VARCHAR(1024) NOT NULL, -- FIXME how long can these be?!
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    markdown TEXT NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    PRIMARY KEY(urn),
    FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of transcriptions:
CREATE TABLE transcriptionCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(1024) NOT NULL, -- FIXME adjust length to transcriptions(urn)!
    FOREIGN KEY(userId) REFERENCES users(userId),
    FOREIGN KEY(urn) REFERENCES transcriptions(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for area of interest specific data:
CREATE TABLE areasOfInterest (
    urn VARCHAR(1024) NOT NULL, -- FIXME how long can these be?!
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    userId BIGINT(20) UNSIGNED NOT NULL,
    scan VARCHAR(1024) NOT NULL, -- FIXME adjust length to scans(urn)!
    PRIMARY KEY(urn),
    FOREIGN KEY(scan) REFERENCES scans(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to store rectangles for an area of interest:
CREATE Table rectangles (
    x INT NOT NULL,
    y INT NOT NULL,
    width INT NOT NULL,
    height INT NOT NULL,
    urn VARCHAR(1024) NOT NULL, -- FIXME adjust length to areasOfInterest(urn) 
    FOREIGN KEY(urn) REFERENCES areasOfInterest(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of areas of interest:
CREATE TABLE aoiCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(1024) NOT NULL, -- FIXME adjust length to areasOfInterest(urn)
    FOREIGN KEY userId REFERENCES users(userId),
    FOREIGN KEY urn REFERENCES areasOfInterest(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for scan specific data:
CREATE TABLE scans (
    urn VARCHAR(1024) NOT NULL, -- FIXME how long can these be?!
    omekaUrl VARCHAR(2000) NOT NULL, -- See https://stackoverflow.com/a/417184/448591
    omekaItem VARCHAR(1024) NOT NULL, -- FIXME adjust length to omekaItems(urn)
    PRIMARY KEY(urn),
    FOREIGN KEY(omekaItem) REFERENCES omekaItems(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of scans:
CREATE TABLE scanCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(1024) NOT NULL, -- FIXME adjust length to scans(urn)
    FOREIGN KEY(userId) REFERENCES users(userId),
    FOREIGN KEY(urn) REFERENCES scans(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to for omeka specific data:
CREATE TABLE omekaItems (
    urn VARCHAR(1024) NOT NULL, -- FIXME how long can these be?!
    omekaUrl VARCHAR(2000) NOT NULL,
    copyright TEXT NOT NULL,
    featured BOOL NOT NULL,
    public BOOL NOT NULL,
    PRIMARY KEY(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Taking things into effect:
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
SET AUTOCOMMIT=1;
