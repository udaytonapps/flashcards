<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

if ( $USER->instructor ) {

    $linkId = $LINK->id;
    $setId = $_POST["linkToSet"];

    $PDOX->queryDie("INSERT INTO {$p}flashcards_link (link_id, SetID) VALUES ( '$linkId', '$setId')",
        array(':linkId' => $linkId, ':setId' => $setId)  );

    header( 'Location: '.addSession('index.php') ) ;
}