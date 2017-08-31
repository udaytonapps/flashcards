<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();

$SetID = $_GET["SetID"];

$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];

$Total7=0;
$NewCardNum = array(); // temp for CardNum2

$rows7 = $PDOX->allRowsDie("SELECT CardNum, CardID FROM flashcards where SetID=".$SetID);
foreach ( $rows7 as $row ) {
    $Total7++;
    array_push($NewCardNum, $row["CardNum"]);
}
//print_r($NewCardNum)."<hr>";
shuffle($NewCardNum);
echo $SetID."<hr>";
for ($x = 1; $x <= $Total7; $x++) {
    $CardNum = $x;

    if($x != $Total7){	$CardNum2 = $NewCardNum[$x];}
    else{$CardNum2 = $NewCardNum[0];}
    echo $CardNum." ".$CardNum2."<br>";

    $PDOX->queryDie("update flashcards set CardNum2=".$CardNum2." where CardNum=".$CardNum." AND SetID=".$SetID);
}



header( 'Location: '.addSession('shuffle.php?CardNum2=1&Flag=A&SetID='.$SetID) ) ;



?>
