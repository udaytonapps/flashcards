<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$setId = $_SESSION["SetID"];
$cardId = $_SESSION["CardId"];
$userId = $USER->id;

if ( $USER->instructor ) {

    $review = $PDOX->rowDie("SELECT * FROM {$p}flashcards_review WHERE UserId = '".$userId."' AND SetId = '".$setId."' AND CardId = '".$cardId."';");

    if(!$review) {
        $PDOX->queryDie("INSERT INTO {$p}flashcards_review (UserId, SetId, CardId) VALUES ('$userId', '$setId', '$cardId');",
            array(':UserId' => $userId, ':SetId' => $setId, ':CardId' => $cardId));
    } else {
        $PDOX->queryDie("DELETE FROM {$p}flashcards_review WHERE UserId = '".$userId."' AND SetId = '".$setId."' AND CardId = '".$cardId."';");
    }
    exit;
}