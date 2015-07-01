-- In this database schema, we've got several fields that
-- aim to store URLs or URNs.
-- For URLs there is a maximum length of 2000 chars that should be anticipated,
-- according to https://stackoverflow.com/a/417184/448591
-- For URNs we go with 2048 wich is a biggish chunk that should suffice.
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
    authenticationMethod VARCHAR(2048) NOT NULL,
    displayName VARCHAR(255) NOT NULL,
    lastLogin TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    tasksCompleted INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for transcription specific data:
CREATE TABLE transcriptions (
    transcriptionId SERIAL,
    urn VARCHAR(2048) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    markdown TEXT NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of transcriptions:
CREATE TABLE transcriptionCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    transcriptionId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(userId) REFERENCES users(userId),
    FOREIGN KEY(transcriptionId) REFERENCES transcriptions(transcriptionId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for area of interest specific data:
CREATE TABLE areasOfInterest (
    aoiId SERIAL,
    urn VARCHAR(2048) NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    userId BIGINT(20) UNSIGNED NOT NULL,
    scanId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(scanId) REFERENCES scans(scanId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to store rectangles for an area of interest:
CREATE Table rectangles (
    x INT NOT NULL,
    y INT NOT NULL,
    width INT NOT NULL,
    height INT NOT NULL,
    aoiId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(aoiId) REFERENCES areasOfInterest(aoiId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of areas of interest:
CREATE TABLE aoiCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    aoiId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(userId) REFERENCES users(userId),
    FOREIGN KEY(aoiId) REFERENCES areasOfInterest(aoiId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for scan specific data:
CREATE TABLE scans (
    scanId SERIAL,
    urn VARCHAR(2048) NOT NULL,
    omekaUrl VARCHAR(2000) NOT NULL,
    omekaItemId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(omekaItemId) REFERENCES omekaItems(omekaItemId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table for votes on the completeness of scans:
CREATE TABLE scanCompleteness (
    isGood BOOL NOT NULL,
    userId BIGINT(20) UNSIGNED NOT NULL,
    scanId BIGINT(20) UNSIGNED NOT NULL,
    FOREIGN KEY(userId) REFERENCES users(userId),
    FOREIGN KEY(scanId) REFERENCES scans(scanId)
) ENGINE = InnoDB CHARACTER SET utf8;
-- Table to for omeka specific data:
CREATE TABLE omekaItems (
    omekaItemId SERIAL,
    urn VARCHAR(2048) NOT NULL,
    omekaUrl VARCHAR(2000) NOT NULL,
    copyright TEXT NOT NULL,
    featured BOOL NOT NULL,
    public BOOL NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8;
-- Taking things into effect:
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
SET AUTOCOMMIT=1;
