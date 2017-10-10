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
$_SESSION["CourseName"] = $CONTEXT->title;

if ( $USER->instructor ) {

    include("menu.php");
    include("instructor-home.php");

}else{ // student

    $linkId = $LINK->id;
    $shortcut = $flashcardsDAO->getShortCutSetIdForLink($linkId);
    if (isset($shortcut["SetId"])) {
        header( 'Location: '.addSession('PlayCard.php?SetID='.$shortcut["SetId"].'&CardNum=1&CardNum2=0&Flag=A&Shortcut=1"') ) ;
    } else {
        include("student-home.php");
    }
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();