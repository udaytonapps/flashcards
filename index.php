<?php
require_once('../config.php');
require_once('dao/FlashcardsDAO.php');

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;

if ( $USER->instructor ) {

    include("menu.php");

    $linkId = $LINK->id;
    $set = $flashcardsDAO->getShortcutSetIdForLink($linkId);
    if (isset($set["SetID"])) {
        include("AllCards.php");
    } else {
        include("instructor-home.php");
    }

}else{ // student

    $linkId = $LINK->id;
    $shortcut = $flashcardsDAO->getShortcutSetIdForLink($linkId);
    if (isset($shortcut["SetID"])) {
        $theSet = $flashcardsDAO->getFlashcardSetById($shortcut["SetID"]);
        if ($theSet["Active"] == 1) {
            header( 'Location: '.addSession('PlayCard.php?SetID='.$shortcut["SetID"].'&CardNum=1&CardNum2=0&Flag=A&Shortcut=1"') ) ;
        } else {
            echo('<h3>Flashcards</h3><p><em>This flashcard set is not currently available.</em></p>');
        }
    } else {
        //include("student-home.php");
        echo('<h3>Flashcards</h3><p><em>This flashcard set is not currently available.</em></p>');
    }
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();