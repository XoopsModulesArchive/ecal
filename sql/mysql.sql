CREATE TABLE eCal (
    id          INT(11) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    username    VARCHAR(255),
    stamp       DATETIME,
    subject     VARCHAR(255),
    description BLOB,
    url         VARCHAR(255),
    valid       VARCHAR(10),
    PRIMARY KEY (id)
);
