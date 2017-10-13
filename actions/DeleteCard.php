<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";
require_once "../util/FlashcardUtils.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$SetID=$_GET["SetID"];
$CardID=$_GET["CardID"];

if ( $USER->instructor ) {

    $flashcardsDAO->deleteCard($CardID);

    $flashcardsDAO->deleteActivityForCard($CardID);

    $remainingCards = $flashcardsDAO->getCardsInSet($SetID);

    usort($remainingCards, array('FlashcardUtils', 'compareCardNum'));

    $CardNum = 0;
    foreach ( $remainingCards as $card ) {
        $CardNum++;
        $flashcardsDAO->updateCardNumber($card["CardID"], $CardNum);
    }

    header( 'Location: '.addSession('../AllCards.php?SetID='.$SetID) ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
