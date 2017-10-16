<?php
require_once "../../config.php";
require_once "../dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$setId = $_SESSION["SetID"];
$cardId = $_SESSION["CardID"];
$userId = $USER->id;

$flashcardsDAO->toggleReview($userId, $setId, $cardId);

exit;
