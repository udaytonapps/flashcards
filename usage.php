<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();


$p = $CFG->dbprefix;

$OUTPUT->header();



include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    include("menu.php");

    $hasRosters = LTIX::populateRoster(false);

    if ($hasRosters) {
        $rosterData = $GLOBALS['ROSTER']->data;
        foreach($rosterData as $test) {
            echo($test["person_name_full"].' ('.$test["person_contact_email_primary"].')<br>');
        }
    }



$SetID = $_GET["SetID"];
$CardSetName = $_GET["CardSetName"];
$Total=0;

/*if($Page == 1 || $Page < 1){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND CardNum < 21";
    $Offset = 0;
    $Page = 1;

}else if($Page == 2){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >20 AND CardNum < 41)";
    $Offset = 20;

}else if($Page == 3){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >40 AND CardNum < 61)";
    $Offset = 40;

}else if($Page == 4){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >60 AND CardNum < 81)";
    $Offset = 60;

}else if($Page == 5){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >80 AND CardNum < 101)";
    $Offset = 80;

}else if($Page == 6){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >100 AND CardNum < 121)";
    $Offset = 100;

}else if($Page == 7){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >120 AND CardNum < 141)";
    $Offset = 120;

}else if($Page == 8){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >140 AND CardNum < 161)";
    $Offset = 140;

}else if($Page == 9){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >160 AND CardNum < 181)";
    $Offset = 160;

}else if($Page == 10){
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID." AND (CardNum >180 AND CardNum < 201)";
    $Offset = 180;

}
else{
    $Page=1;
    $SQL0="SELECT * FROM flashcards where SetID=".$SetID;
    $Offset = 0;
}






$rows0 = $PDOX->allRowsDie($SQL0);
foreach ( $rows0 as $row ) {
    $Total++;
}

//echo $Total;


    $rows = $PDOX->allRowsDie("SELECT DISTINCT FullName FROM flashcards_activity where SetID=".$SetID);
    $PagePrev = $Page-1;
    $Page++;


//echo "<h3>".$CardSetName."</h3><br>";

    echo "<div style='width:900px'>";
    if ($Page >2){
        echo "<a href='usage.php?Page=".$PagePrev."&SetID=".$SetID."&PHPSESSID=".$_GET["PHPSESSID"]."&CardSetName=".$CardSetName."'><img src='images/Prev.png' width='50'></a>";
    }else{ echo "<img src='images/Blank.png' width='50'>";}
    if ($Total>19){
        echo "<a style='float:right;' href='usage.php?Page=".$Page."&SetID=".$SetID."&PHPSESSID=".$_GET["PHPSESSID"]."&CardSetName=".$CardSetName."'><img src='images/Next.png' width='50'></a>";
    }

    echo "<table class='table table-bordered table-hover'  style='width:auto'>";
    echo "<tr><th class='SName'> Student Name </th>";

    for ($h = 1; $h <= $Total; $h++) {


        $NewCardNum = $h+$Offset;

        echo "<th class='checkMark'>".$NewCardNum."</th>";
    }

    echo"</tr>";

    foreach ( $rows as $row ) {

        echo "<tr>";

        echo "<td style= 'padding: 5px;'>".$row["FullName"]."</td>";

        // for each card
        for ($x = 1; $x <= $Total; $x++) {

            $viewed=0;
            $NewCardNum = $x+$Offset;
            $rows9 = $PDOX->allRowsDie("SELECT * FROM flashcards_activity where FullName='".$row["FullName"]."' AND SetID=".$SetID);
            foreach ( $rows9 as $row9 ) {
                if ($row9["CardNum"] == $NewCardNum){$viewed++;
                    //echo $row9["ActivityID"].",".$row9["CardNum"].",".$NewCardNum."<hr>";
                }


            }
            if ($viewed >0){$check="<center><img class='checkMark' src='images/CM.PNG'></center>";}
            else{$check="";}


            echo "<td style= 'padding: 0px; width:40px; '>".$check."</td>";
        }



        echo "</tr>\n";
    }
    echo("</table></div>"); */


} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
