<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$userId = $USER->id;
$setId = $_SESSION["SetID"];

$flashcardsDAO->deleteAllCardReviews($userId, $setId);

if(isset($_GET["ReviewMode"])){
    $isReviewMode = $_GET["ReviewMode"];
} else {
    $isReviewMode = 0;
}

header( 'Location: '.addSession('../PlayCard.php?SetID='.$setId.'&CardNum=1&CardNum2=0&Flag=A&Shortcut='.$_SESSION["Shortcut"].'&ReviewMode='.$isReviewMode) ) ;