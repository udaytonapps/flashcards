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

if ( $USER->instructor ) {

    // Delete all cards
    $flashcardsDAO->deleteAllCardsInSet($SetID);

    // Delete set
    $flashcardsDAO->deleteCardSet($SetID);

}

header( 'Location: '.addSession('../index.php') ) ;
