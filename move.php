<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_GET["SetID"];
$CardID=$_GET["CardID"];
$CardNum = $_GET["CardNum"];  // 6 by CardID -  1233
$Flag = $_GET["Flag"];
if ($Flag){$NewCardNum = $CardNum-1;} // 5
else{$NewCardNum = $CardNum+1;} // 7





if ( $USER->instructor ) {

// find NewCardID for 5

    $rows = $PDOX->allRowsDie("SELECT CardID FROM flashcards where CardNum=".$NewCardNum." AND SetID=".$SetID);

    foreach ( $rows as $row ) {
        $NewCardID = $row["CardID"];// 1229
    }


// Swap

    $PDOX->queryDie("update flashcards set CardNum=".$CardNum." where CardID=".$NewCardID);    // 5-> 6
    $PDOX->queryDie("update flashcards set CardNum=".$NewCardNum." where CardID=".$CardID);    // 6-> 5


    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
