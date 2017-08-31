<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_GET["SetID"];

if ( $USER->instructor ) {
    //$PDOX->queryDie("update flashcardset set Visible=0 where SetID=".$SetID);

    $PDOX->queryDie("DELETE FROM flashcards_set where SetID=".$SetID);


    header( 'Location: '.addSession('index.php') ) ;
}
