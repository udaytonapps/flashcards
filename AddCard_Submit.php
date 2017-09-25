<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$TypeA = $_POST["TypeA"];
$TypeB = $_POST["TypeB"];

$SetID=$_POST["SetID"];
$SideA = $_POST["SideA"];
$SideB = $_POST["SideB"];
$SideA = str_replace("'", "&#39;", $SideA);
$SideB = str_replace("'", "&#39;", $SideB);
$CardNum = $_POST["CardNum"];
$Next = $CardNum +1;

if ( $USER->instructor ) {

    $PDOX->queryDie("INSERT INTO {$p}flashcards (SetID, CardNum, SideA, SideB, TypeA, TypeB) VALUES ( $SetID, $CardNum, '$SideA', '$SideB', '$TypeA', '$TypeB' )",
        array(':SetID' => $SetID, ':CardNum' => $CardNum, ':SideA' => $SideA,':SideB' => $SideB,':TypeA' => $TypeA,':TypeB' => $TypeB)  );
    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
