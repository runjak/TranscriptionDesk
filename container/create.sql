-- In this database schema, we've got several fields that
-- aim to store URLs or URNs.
-- For URLs there is a maximum length of 2000 chars that should be anticipated,
-- according to https://stackoverflow.com/a/417184/448591
-- For URNs we got with 250, which turns out to be an upper limit because of [1,2].
-- [1]: https://dev.mysql.com/doc/refman/5.6/en/innodb-restrictions.html
-- [2]: https://stackoverflow.com/q/6172798/448591
-- This whole script shall run as a single transaction:
SET AUTOCOMMIT=0;
SET FOREIGN_KEY_CHECKS=0;
-- Making sure to drop tables if necessary:
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `transcriptions`;
DROP TABLE IF EXISTS `transcriptionCompleteness`;
DROP TABLE IF EXISTS `areasOfInterest`;
DROP TABLE IF EXISTS `aoiCompleteness`;
DROP TABLE IF EXISTS `rectangles`;
DROP TABLE IF EXISTS `scans`;
DROP TABLE IF EXISTS `scanCompleteness`;
DROP TABLE IF EXISTS `omekaItems`;
-- Creating tables from scratch:
-- Table for user data:
CREATE TABLE users (
    userId SERIAL,
    authenticationMethod VARCHAR(255) NOT NULL UNIQUE,
    displayName VARCHAR(255) NOT NULL,
    avatarUrl VARCHAR(2000),
    lastLogin TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    tasksCompleted INT UNSIGNED NOT NULL DEFAULT 0,
    isAdmin BOOL NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for transcription specific data:
CREATE TABLE transcriptions (
    urn VARCHAR(250) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    markdown TEXT NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    PRIMARY KEY (urn),
    FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of transcriptions:
CREATE TABLE transcriptionCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(250) NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(userId),
    FOREIGN KEY (urn) REFERENCES transcriptions(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for area of interest specific data:
CREATE TABLE areasOfInterest (
    urn VARCHAR(250) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    userId BIGINT(20) UNSIGNED NOT NULL,
    scan VARCHAR(250) NOT NULL,
    PRIMARY KEY (urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to store rectangles for an area of interest:
CREATE Table rectangles (
    x DOUBLE NOT NULL,
    y DOUBLE NOT NULL,
    width DOUBLE NOT NULL,
    height DOUBLE NOT NULL,
    urn VARCHAR(250) NOT NULL,
    FOREIGN KEY (urn) REFERENCES areasOfInterest(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of areas of interest:
CREATE TABLE aoiCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(250) NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(userId),
    FOREIGN KEY (urn) REFERENCES areasOfInterest(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for scan specific data:
CREATE TABLE scans (
    urn VARCHAR(250) NOT NULL,
    omekaUrl VARCHAR(2000) NOT NULL,
    omekaItem VARCHAR(250) NOT NULL,
    PRIMARY KEY (urn),
    FOREIGN KEY (omekaItem) REFERENCES omekaItems(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to map AOIs to scans:
CREATE TABLE scanAoiMap (
    scanUrn VARCHAR(250) NOT NULL,
    aoiUrn VARCHAR(250) NOT NULL,
    FOREIGN KEY (scanUrn) REFERENCES scans(urn),
    FOREIGN KEY (aoiUrn) REFERENCES areasOfInterest(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of scans:
CREATE TABLE scanCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    urn VARCHAR(250) NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(userId),
    FOREIGN KEY (urn) REFERENCES scans(urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to for omeka specific data:
CREATE TABLE omekaItems (
    urn VARCHAR(250) NOT NULL,
    omekaUrl VARCHAR(2000) NOT NULL,
    copyright TEXT NOT NULL,
    featured BOOL NOT NULL,
    public BOOL NOT NULL,
    PRIMARY KEY (urn)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Taking things into effect:
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
SET AUTOCOMMIT=1;
