<?php
require_once "../../config.php";
require_once('../dao/FlashcardsDAO.php');

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$CardSetName = str_replace("'", "&#39;", $_POST["CardSetName"]);

if ( $USER->instructor ) {

    $newSetId = $flashcardsDAO->createCardSet($USER->id, $CONTEXT->id, $CardSetName);

    header( 'Location: '.addSession('../AllCards.php?SetID='.$newSetId) ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
