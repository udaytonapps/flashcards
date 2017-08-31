<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_POST["SetID"];
$Active = $_POST["Active"];
$CardSetName = $_POST["CardSetName"];
$CardSetName = str_replace("'", "&#39;", $CardSetName);

if ( $USER->instructor ) {

    $PDOX->queryDie("update flashcards_set set CardSetName='".$CardSetName."', Active=".$Active." where SetID=".$SetID);
    header( 'Location: '.addSession('index.php') ) ;
}


$OUTPUT->footer();


