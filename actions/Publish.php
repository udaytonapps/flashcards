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
$Flag = $_GET["Flag"];

if ( $USER->instructor ) {

    $flashcardsDAO->togglePublishCardSet($setId, $toggle);
}

header( 'Location: '.addSession('../index.php') ) ;
