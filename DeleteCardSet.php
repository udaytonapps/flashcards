<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID=$_GET["SetID"];

if ( $USER->instructor ) {

    // Delete all cards
    $PDOX->queryDie("DELETE FROM {$p}flashcards where SetID=".$SetID.";");

    // Delete set
    $PDOX->queryDie("DELETE FROM {$p}flashcards_set where SetID=".$SetID.";");

    header( 'Location: '.addSession('index.php') ) ;
}
