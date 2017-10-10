<?php
require_once "../../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

if ( $USER->instructor ) {

    $linkId = $LINK->id;
    $setId = $_POST["linkToSet"];

    $previousLink = $PDOX->rowDie("SELECT * FROM {$p}flashcards_link WHERE link_id ='".$linkId."';");

    if(isset($previousLink["SetID"])) {
        $PDOX->queryDie("UPDATE {$p}flashcards_link SET SetID = '".$setId."' WHERE link_id ='".$linkId."';");
    } else {
        $PDOX->queryDie("INSERT INTO {$p}flashcards_link (link_id, SetID) VALUES ( '$linkId', '$setId')",
            array(':linkId' => $linkId, ':setId' => $setId)  );
    }

    header( 'Location: '.addSession('../index.php') ) ;
}