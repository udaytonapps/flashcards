<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$setId = $_SESSION["SetID"];
$userId = $USER->id;

$PDOX->queryDie("DELETE FROM {$p}flashcards_review WHERE UserId = '".$userId."' AND SetId = '".$setId."';");

if(isset($_GET["ReviewMode"])){
    $isReviewMode = $_GET["ReviewMode"];
} else {
    $isReviewMode = 0;
}

header( 'Location: '.addSession('playcard.php?SetID='.$setId.'&CardNum=1&CardNum2=0&Flag=A&Shortcut='.$_SESSION["Shortcut"].'&ReviewMode='.$isReviewMode) ) ;