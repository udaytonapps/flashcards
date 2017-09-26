<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID=$_GET["SetID"];
$CardID=$_GET["CardID"];

if ( $USER->instructor ) {

    $PDOX->queryDie("DELETE FROM {$p}flashcards where CardID=".$CardID.";");

    $CardNum = 0;

    $remainingCards = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$_GET["SetID"]." order by CardNum;");

    foreach ( $remainingCards as $card ) {
        $CardNum++;
        $PDOX->queryDie("update {$p}flashcards set CardNum=".$CardNum." where CardID=".$card["CardID"].";");
    }

    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
