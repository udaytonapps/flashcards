<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_GET["SetID"];
$CardID=$_GET["CardID"];


if ( $USER->instructor ) {

    $PDOX->queryDie("DELETE FROM flashcards where CardID=".$CardID);



    $CardNum = 0;

    $rows1 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_GET["SetID"]." order by CardNum");
    foreach ( $rows1 as $row ) {
        $CardNum++;
        $PDOX->queryDie("update flashcards set CardNum=".$CardNum." where CardID=".$row["CardID"]);
    }

    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
