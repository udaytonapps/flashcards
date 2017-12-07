<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$SetID = $_GET["SetID"];

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

$allCards = $flashcardsDAO->getCardsInSet($SetID);

$TotalCards = count($allCards);

foreach ( $allCards as $card ) {
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

    $flashcardsDAO->updateCardNumber2($CardNum2, $CardNum, $SetID);
}

header( 'Location: '.addSession('../PlayCard.php?CardNum=0&CardNum2=1&Flag=A&SetID='.$SetID.'&Shortcut='.$shortCut.'&ReviewMode='.$reviewMode) ) ;
