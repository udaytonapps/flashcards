<?php
require_once "../../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$SetID = $_GET["SetID"];

$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];

$TotalCards = 0;

if(isset($_GET["Shortcut"])) {
    $shortCut = $_GET["Shortcut"];
} else {
    $shortCut = 0;
}

if(isset($_GET["ReviewMode"])) {
    $reviewMode = $_GET["ReviewMode"];
} else {
    $reviewMode = 0;
}

$NewCardNum = array(); // temp for CardNum2

$allCards = $PDOX->allRowsDie("SELECT CardNum, CardID FROM {$p}flashcards where SetID=".$SetID.";");

foreach ( $allCards as $card ) {
    $TotalCards++;
    array_push($NewCardNum, $card["CardNum"]);
}

shuffle($NewCardNum);

for ($x = 1; $x <= $TotalCards; $x++) {
    $CardNum = $x;

    if ($x != $TotalCards) {
        $CardNum2 = $NewCardNum[$x];
    } else {
        $CardNum2 = $NewCardNum[0];
    }

    $PDOX->queryDie("update {$p}flashcards set CardNum2=".$CardNum2." where CardNum=".$CardNum." AND SetID=".$SetID);
}

header( 'Location: '.addSession('../PlayCard.php?CardNum=0&CardNum2=1&Flag=A&SetID='.$SetID.'&Shortcut='.$shortCut.'&ReviewMode='.$reviewMode) ) ;
