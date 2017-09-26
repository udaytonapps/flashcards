<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ($_GET["Shuffle"] == 1) {

}


$CardNum = $_GET["CardNum"];
$_SESSION["CardNum"] = $CardNum;
$Next = $CardNum + 1;
$Prev = $CardNum - 1;
$Flag = $_GET["Flag"];
$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];
$Total=0;

if ( $USER->instructor ) {
    include("menu.php");
} else {
    echo('
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Flashcards</a>
            </div>
        </div>
    </nav>
    ');
}

$setId = $_GET["SetID"];
$_SESSION["SetID"] = $setId;

$cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId." order by CardNum;");

$set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

$Total = count($cardsInSet);

$percentComplete = $CardNum / $Total * 100;

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li>'.$set["CardSetName"].'</li>
        </ul>
        
        <div class="row cardRow">
            <div class="col-sm-3 play-menu">
                <h3>'.$set["CardSetName"].'</h3>
                <span>Progress <strong>'.$CardNum.'/'.$Total.'</strong></span>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$percentComplete.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentComplete.'%">
                        <span class="sr-only">'.$percentComplete.'% Complete</span>
                    </div>
                </div>
                <a class="btn btn-primary" href="playcard.php?SetID='.$set["SetID"].'&Flag=A&Shuffle=1"><span class="fa fa-random"></span> Shuffle Cards</a>            
            </div>
            <div class="col-sm-9">    
    ');

    $theCard = $PDOX->rowDie("SELECT * FROM {$p}flashcards where SetID=".$setId." AND CardNum=".$CardNum.";");

    echo('
        <div id="play-card-container">
            <div class="front">
                <span class="h4 text-muted">Side A</span>
                <div class="play-card text-center">
                    <span>');
                        if ($theCard["TypeA"] == "Image") {
                            echo('<img src="'.$theCard["SideA"].'">');
                        } else if ($theCard["TypeA"] == "mp3") {
                            echo('<audio controls><source src="'.$theCard["SideA"].'" type="audio/mpeg">Your browser does not support the audio element.</audio>');
                        } else if ($theCard["TypeA"] == "Video") {
                            $URL = $theCard["SideA"];
                            if (strpos($URL, 'youtube') == true) {
                                if (strpos($URL, 'embed') == false) {
                                    $Youtube = $theCard["SideA"];
                                    $Code = explode("=", $Youtube);
                                    $URL = "https://www.youtube.com/embed/".$Code[1];
                                }
                            }
                            echo ('<div class="video-container"><iframe src="'.$URL.'" class="video" frameborder="0"></iframe></div>');
                        } else {
                            echo($theCard["SideA"]);
                        }
                    echo('</span>
                </div>
                <span class="h3 text-muted"><span class="fa fa-undo"></span> Click to flip</span>
            </div>
            <div class="back">
                <span class="h4 text-muted">Side B</span>
                <div class="play-card text-center">
                    <span>');
                        if ($theCard["TypeB"] == "Image") {
                            echo('<img src="'.$theCard["SideB"].'">');
                        } else {
                            echo($theCard["SideB"]);
                        }
                    echo('</span></div>
                <span class="h3 text-muted"><span class="fa fa-undo"></span> Click to flip</span>
            </div>                    
        </div>
        
        <input type="hidden" id="sess" value="'.$_GET["PHPSESSID"].'">
        
        <div class="prev-next text-center">
            <a href="playcard.php?SetID='.$setId.'&CardNum='.$Prev.'&Flag=A" ');if($Prev == 0){echo('class="disabled"');} echo('>
                <span class="fa fa-3x fa-chevron-circle-left"></span>
            </a>
            <a href="playcard.php?SetID='.$setId.'&CardNum='.$Next.'&Flag=A" ');if($Next > $Total){echo('class="disabled"');} echo('>
                <span class="fa fa-3x fa-chevron-circle-right"></span>
            </a>
        </div>
    ');

$OUTPUT->footerStart();

include("tool-footer.html");

echo('<script src="scripts/jquery.flip.min.js" type="text/javascript"></script>');
echo('<script src="scripts/cardflipper.js" type="text/javascript"></script>');

$OUTPUT->footerEnd();
