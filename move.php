<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID=$_GET["SetID"];
$CardID=$_GET["CardID"];
$CardNum = $_GET["CardNum"];

$Flag = $_GET["Flag"];

if ($Flag){
    $NewCardNum = $CardNum-1;
} else {
    $NewCardNum = $CardNum+1;
}

if ( $USER->instructor ) {

    $oldCard = $PDOX->rowDie("SELECT CardID FROM {$p}flashcards where CardNum=".$NewCardNum." AND SetID=".$SetID);
    $NewCardID = $oldCard["CardID"];

    $PDOX->queryDie("update {$p}flashcards set CardNum=".$CardNum." where CardID=".$NewCardID);    // 5-> 6
    $PDOX->queryDie("update {$p}flashcards set CardNum=".$NewCardNum." where CardID=".$CardID);    // 6-> 5

    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
