<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

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

    $swapCard = $flashcardsDAO->getCardBySetAndNumber($SetID, $NewCardNum);

    $flashcardsDAO->updateCardNumber($swapCard["CardID"], $CardNum);
    $flashcardsDAO->updateCardNumber($CardID, $swapCard["CardNum"]);

    header( 'Location: '.addSession('../AllCards.php?SetID='.$SetID) ) ;
} else {
    header( 'Location: '.addSession('../index.php') ) ;
}
