<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
    /*
     * "drop table if exists {$CFG->dbprefix}flashcards_link"
     * We probably want to keep these records even if the tool
     * is uninstalled.
     */
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
    array( "{$CFG->dbprefix}flashcards_set",
        "create table {$CFG->dbprefix}flashcards_set (
    SetID       INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    context_id  INTEGER NULL,
    CardSetName varchar(255) NULL,
    Category    varchar(50) NULL,
    Modified    datetime NULL,
    Active      int(1) DEFAULT '0',
    Visible     int(1) DEFAULT '1',
  
    PRIMARY KEY(SetID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}flashcards_link",
        "create table {$CFG->dbprefix}flashcards_link (
    link_id     INTEGER NOT NULL,
    SetID       INTEGER NULL,

    CONSTRAINT `{$CFG->dbprefix}flashcards_link_ibfk_2`
        FOREIGN KEY (`SetID`)
        REFERENCES `{$CFG->dbprefix}flashcards_set` (`SetID`)
        ON UPDATE CASCADE,
        
    PRIMARY KEY(link_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}flashcards",
        "create table {$CFG->dbprefix}flashcards (
    CardID      INTEGER NOT NULL AUTO_INCREMENT,
    SetID       INTEGER NULL,
    CardNum     INTEGER NULL,
    CardNum2    INTEGER NULL,
    SideA       varchar(1500) NULL,
    SideB       varchar(1500) NULL,
    File        varchar(255) DEFAULT '0',
    Ref1        varchar(255) NULL,
    Ref2        varchar(255) NULL,
    Ref3        varchar(255) NULL,
    Youtube     varchar(1000) NULL,
    Editor      varchar(20) NULL,
    Modified    datetime NULL,
    TypeA       varchar(5) DEFAULT 'Text',
    TypeB       varchar(5) DEFAULT 'Text',
  
    CONSTRAINT `{$CFG->dbprefix}flashcards_ibfk_1`
        FOREIGN KEY (`SetID`)
        REFERENCES `{$CFG->dbprefix}flashcards_set` (`SetID`)
        ON UPDATE CASCADE,

    PRIMARY KEY(CardID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}flashcards_activity",
        "create table {$CFG->dbprefix}flashcards_activity (
    ActivityID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,
    CardID      INTEGER NOT NULL,
    Modified    datetime NULL,
  
    PRIMARY KEY(ActivityID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}flashcards_review",
        "create table {$CFG->dbprefix}flashcards_review (
    UserID      INTEGER NOT NULL,
    SetID       INTEGER NOT NULL,
    CardID      INTEGER NOT NULL,
  
    PRIMARY KEY(UserID, SetID, CardID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);
