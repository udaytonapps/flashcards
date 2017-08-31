<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$SetID=$_POST["SetID"];
$CardID=$_POST["CardID"];
$SideA = $_POST["SideA"];
$SideB = $_POST["SideB"];
$TypeA = $_POST["TypeA"];
$TypeB = $_POST["TypeB"];


$SideA = str_replace("'", "&#39;", $SideA);
$SideB = str_replace("'", "&#39;", $SideB);



if ( $USER->instructor ) {

    $PDOX->queryDie("update flashcards set SideA='".$SideA."', SideB='".$SideB."',TypeA='".$TypeA."', TypeB='".$TypeB."' where CardID=".$CardID);
    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;
}
