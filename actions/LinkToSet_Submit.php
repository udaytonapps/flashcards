<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

if ( $USER->instructor ) {

    $linkId = $LINK->id;
    $setId = $_POST["linkToSet"];

    $flashcardsDAO->saveOrUpdateLink($setId, $linkId);

}

header( 'Location: '.addSession('../index.php') ) ;
