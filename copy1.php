<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

if ( $USER->instructor ) {

    $SetID1 = $_GET["SetID"];
    $UserName = $_SESSION["UserName"];
    $CourseName = $_SESSION["CourseName"];
    $Total3=0;

    $oCardSet = $PDOX->rowDie("SELECT * FROM {$p}flashcards_set where SetID=".$SetID1.";");

    $CardSetName = $oCardSet["CardSetName"];

    $PDOX->queryDie("INSERT INTO {$p}flashcards_set (UserName,CourseName, CardSetName) VALUES ( '$UserName','$CourseName', '$CardSetName' );",
        array(':UserName' => $UserName,':CourseName' => $CourseName,':CardSetName' => $CardSetName)  );


    // for new SetID

    $rows2 = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards_set where CourseName='".$CourseName."' AND CardSetName='".$CardSetName."';");
    foreach ( $rows2 as $row ) {
        $SetID2 = $row["SetID"];
    }

    // get flashcard

    $CardSideA = array();
    $CardSideB = array();
    $CardTypeA = array();
    $CardTypeB = array();
    $CardNumSet=array();

    $rows3 = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$SetID1.";");
    foreach ( $rows3 as $row ) {
        $Total3++;
        array_push($CardSideA, $row["SideA"]);
        array_push($CardSideB, $row["SideB"]);
        array_push($CardTypeA, $row["TypeA"]);
        array_push($CardTypeB, $row["TypeB"]);
        array_push($CardNumSet, $row["CardNum"]);
    }

    for ($x = 0; $x < $Total3; $x++) {
        $PDOX->queryDie("INSERT INTO {$p}flashcards (SetID, CardNum, SideA, SideB, TypeA, TypeB) VALUES ( $SetID2, $CardNumSet[$x], '$CardSideA[$x]', '$CardSideB[$x]', '$CardTypeA[$x]', '$CardTypeB[$x]' )",
            array(':SetID' => $SetID2, ':CardNum' => $CardNum, ':SideA' => $SideA, ':SideB' => $SideB, ':TypeA' => $TypeA, ':TypeB' => $TypeB));
    }

    header( 'Location: '.addSession('index.php') ) ;
}