<?php
require_once "../../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$setId = $_SESSION["SetID"];
$CardNum = $_SESSION["CardNum"];
$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];

$activity = $PDOX->rowDie("SELECT * FROM {$p}flashcards_activity where SetID='".$setId."' AND CardNum='".$CardNum."' AND UserName='".$UserName."';");

if (!$activity) {
    $PDOX->queryDie("INSERT INTO {$p}flashcards_activity (SetID, CardNum, UserName, FullName) VALUES ( $setId, $CardNum, '$UserName', '$FullName' );",
        array(':SetID' => $setId, ':CardNum' => $CardNum, ':UserName' => $UserName,':FullName' => $FullName)  );
}
