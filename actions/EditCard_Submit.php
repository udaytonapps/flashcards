<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$SetID=$_POST["SetID"];

$CardID=$_POST["CardID"];

$TypeA = $_POST["TypeA"];
$TypeB = $_POST["TypeB"];

$SideA = str_replace("'", "&#39;", $_POST["SideA"]);
$SideB = str_replace("'", "&#39;", $_POST["SideB"]);

if ( $USER->instructor ) {

    $flashcardsDAO->updateCard($CardID, $SideA, $SideB, $TypeA, $TypeB);

    header( 'Location: '.addSession('../index.php') ) ;

} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
