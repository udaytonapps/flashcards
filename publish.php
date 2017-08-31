<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_GET["SetID"];
$Flag = $_GET["Flag"];

if ( $USER->instructor ) {

    $PDOX->queryDie("update flashcards_set set Active=".$Flag." where SetID=".$SetID);
    header( 'Location: '.addSession('index.php') ) ;
}


$OUTPUT->footer();


