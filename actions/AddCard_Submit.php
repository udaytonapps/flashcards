<?php
require_once "../../config.php";
require_once('../dao/FlashcardsDAO.php');

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$TypeA = $_POST["TypeA"];
$TypeB = $_POST["TypeB"];

$SetID=$_POST["SetID"];

$SideA = str_replace("'", "&#39;", $_POST["SideA"]);
$SideB = str_replace("'", "&#39;", $_POST["SideB"]);

$CardNum = $_POST["CardNum"];

if ( $USER->instructor ) {

    $flashcardsDAO->createCard($SetID, $CardNum, $SideA, $SideB, $TypeA, $TypeB);

    header( 'Location: '.addSession('../AllCards.php?SetID='.$SetID) ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
