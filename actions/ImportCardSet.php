<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

if ( $USER->instructor ) {

    $oSetId = $_GET["SetID"];

    $oCardSet = $flashcardsDAO->getFlashcardSetById($oSetId);

    $CardSetName = $oCardSet["CardSetName"];

    $newSetId = $flashcardsDAO->createCardSet($USER->id, $CONTEXT->id, $CardSetName);

    $oCardsInSet = $flashcardsDAO->getCardsInSet($oSetId);

    usort($oCardsInSet, array('FlashcardUtils', 'compareCardNum'));

    $cardNum = 1;
    foreach ($oCardsInSet as $card) {
        $flashcardsDAO->createCard($newSetId, $cardNum, $card["SideA"], $car["MediaA"], $card["SideB"], $card["MediaB"], $card["TypeA"], $card["TypeB"]);
        ++$cardNum;
    }
}

header( 'Location: '.addSession('../index.php') ) ;
