<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID=$_GET["SetID"];
$Flag = $_GET["Flag"];

if ( $USER->instructor ) {

    $PDOX->queryDie("update {$p}flashcards_set set Active=".$Flag." where SetID=".$SetID);

    header( 'Location: '.addSession('index.php') ) ;
}


