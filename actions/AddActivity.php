<?php
require_once "../../config.php";
require_once("../dao/FlashcardsDAO.php");

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$setId = $_SESSION["SetID"];
$cardId = $_SESSION["CardID"];

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$flashcardsDAO->updateActivityForUserCard($setId, $cardId, $USER->id);

exit;
