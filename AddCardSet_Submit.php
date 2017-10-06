<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$UserName=$_SESSION["UserName"];
$CourseName = $_POST["CourseName"];
$CardSetName = $_POST["CardSetName"];

$CardSetName = str_replace("'", "&#39;", $CardSetName);

if ( $USER->instructor ) {

    $PDOX->queryDie("INSERT INTO {$p}flashcards_set (UserName, CourseName, CardSetName) VALUES ( '$UserName', '$CourseName', '$CardSetName')",
        array(':UserName' => $UserName, ':CourseName' => $CourseName, ':CardSetName' => $CardSetName)  );

    header( 'Location: '.addSession('list.php?SetID='.$PDOX->lastInsertId()) ) ;
}
