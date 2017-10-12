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
$Active = $_POST["Active"];
$CardSetName = str_replace("'", "&#39;", $_POST["CardSetName"]);

if ( $USER->instructor ) {

    $flashcardsDAO->updateCardSet($SetID, $CardSetName, $Active);
}

header( 'Location: '.addSession('../index.php') ) ;
