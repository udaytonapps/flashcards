<?php
require_once "../../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

if ( $USER->instructor ) {

    $linkId = $LINK->id;

    $PDOX->queryDie("DELETE FROM {$p}flashcards_link WHERE link_id = '".$linkId."';");

    header( 'Location: '.addSession('../index.php') ) ;
}