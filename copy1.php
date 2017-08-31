<?php
require_once "../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// View
$OUTPUT->header();
$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    include("menu.php");

    $SetID1 = $_GET["SetID"];
    $UserName = $_SESSION["UserName"];
    $CourseName = $_SESSION["CourseName"];
    $Total3=0;


    $rows1 = $PDOX->allRowsDie("SELECT * FROM flashcards_set where SetID=".$SetID1);
    foreach ( $rows1 as $row ) {
        $CardSetName = $row["CardSetName"];

    }

    $PDOX->queryDie("INSERT INTO flashcards_set (UserName,CourseName, CardSetName) VALUES ( '$UserName','$CourseName', '$CardSetName' )",
        array(':UserName' => $UserName,':CourseName' => $CourseName,':CardSetName' => $CardSetName)  );


    // for new SetID

    $rows2 = $PDOX->allRowsDie("SELECT * FROM flashcards_set where CourseName='".$CourseName."' AND CardSetName='".$CardSetName."'");
    foreach ( $rows2 as $row ) {
        $SetID2 = $row["SetID"];
    }

//echo "Original SetID=".$SetID1."<br>";
//echo "New SetID=".$SetID2;


// get flashcard

    $CardSetA = array();
    $CardSetB = array();
    $CardNumSet=array();

    $rows3 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$SetID1);
    foreach ( $rows3 as $row ) {
        $Total3++;
        array_push($CardSetA, $row["SideA"]);
        array_push($CardSetB, $row["SideB"]);
        array_push($CardNumSet, $row["CardNum"]);
    }



    for ($x = 0; $x < $Total3; $x++) {
        //echo $CardNumSet[$x].". ";
        //echo $CardSetA[$x]." = ";
        //echo $CardSetA[$x]."<br>";



        $PDOX->queryDie("INSERT INTO flashcards (SetID, CardNum, SideA, SideB) VALUES ( $SetID2, $CardNumSet[$x], '$CardSetA[$x]', '$CardSetB[$x]' )",
            array(':SetID' => $SetID2, ':CardNum' => $CardNum, ':SideA' => $SideA,':SideB' => $SideB)  );

    }// for loop



    ?>



    <?php


    $_SESSION['success'] = __('New Cardset has been copied!');
    header( 'Location: '.addSession('index.php') ) ;


}// for instructor

$OUTPUT->footer();