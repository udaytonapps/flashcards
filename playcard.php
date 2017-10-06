<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$CardNum = $_GET["CardNum"];
$CardNum2 = $_GET["CardNum2"];

$_SESSION["CardNum"] = $CardNum;

$Flag = $_GET["Flag"];
$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];
$Total=0;

if(isset($_GET["Shortcut"])) {
    $shortCut = $_GET["Shortcut"];
} else {
    $shortCut = 0;
}


if ( $USER->instructor ) {
    include("menu.php");
} else {
    if ($shortCut == 0) {
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
}

$setId = $_GET["SetID"];
$_SESSION["SetID"] = $setId;

$cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId.";");

$set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

$Total = count($cardsInSet);

if ($CardNum == 0) {
    // We are in "shuffle" mode
    $Next = 0;
    $Prev = 0;
    $Next2 = $CardNum2 + 1;
    $Prev2 = $CardNum2 - 1;
    $percentComplete = $CardNum2 / $Total * 100;
    $theCard = $PDOX->rowDie("SELECT * FROM {$p}flashcards where SetID=".$setId." AND CardNum2=".$CardNum2.";");
    $onCard = $CardNum2;
} else {
    $Next = $CardNum + 1;
    $Prev = $CardNum - 1;
    $Next2 = 0;
    $Prev2 = 0;
    $percentComplete = $CardNum / $Total * 100;
    $theCard = $PDOX->rowDie("SELECT * FROM {$p}flashcards where SetID=".$setId." AND CardNum=".$CardNum.";");
    $onCard = $CardNum;
}

$_SESSION["CardId"] = $theCard["CardID"];

$cardKnown = $PDOX->rowDie("SELECT * FROM {$p}flashcards_review WHERE UserId = '".$USER->id."' AND SetId ='".$setId."' AND CardId = '".$theCard["CardID"]."';");

    if ($shortCut == 0) {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Card Sets</a></li>
                <li>'.$set["CardSetName"].'</li>
            </ul>
        ');
    }

    echo('
        <div class="row cardRow">
            <div class="col-sm-3 play-menu">
                <h3>'.$set["CardSetName"].'</h3>
                <span>Progress <strong>'.$onCard.'/'.$Total.'</strong></span>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$percentComplete.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentComplete.'%">
                        <span class="sr-only">'.$percentComplete.'% Complete</span>
                    </div>
                </div>
                <p>
                    <a class="btn btn-primary" href="shuffle.php?SetID='.$set["SetID"].'&Shortcut='.$shortCut.'"><span class="fa fa-random"></span> Shuffle Cards</a>                
                </p>
                <p>');

                if(isset($_GET["ReviewMode"]) && $_GET["ReviewMode"] == 1) {
                    echo('<a class="btn btn-success" href="playcard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A&ReviewMode=0"><span class="fa fa-check-square-o"></span> Review Mode</a>');
                } else {
                    echo('<a class="btn btn-default" href="playcard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A&ReviewMode=1"><span class="fa fa-square-o"></span> Review Mode</a>');
                }

                echo('</p>
            </div>
            
            <div class="col-sm-9" id="play-card-column">    
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
                    <div class="back" style="visibility: hidden;">
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

                <a id="toggle-review-card" href="javascript:void(0)">
                ');

                    if(!$cardKnown) {
                        echo('<span class="fa fa-square-o">');
                    } else {
                        echo('<span class="fa fa-check-square-o">');
                    }

                echo('</span> I know this card</a>
                        
                <div class="prev-next text-center">
                    <a href="playcard.php?SetID='.$setId.'&CardNum='.$Prev.'&CardNum2='.$Prev2.'&Flag=A&Shortcut='.$shortCut.'" ');if($Prev == 0 && $Prev2 == 0){echo('class="disabled"');} echo('>
                        <span class="fa fa-3x fa-chevron-circle-left"></span>
                    </a>
                    <a href="javascript:void(0);" id="flip-link">
                        <span class="fa fa-3x fa-undo"></span>
                    </a>
                    <a id="next-link" href="playcard.php?SetID='.$setId.'&CardNum='.$Next.'&CardNum2='.$Next2.'&Flag=A&Shortcut='.$shortCut.'" ');if($Next > $Total || $Next2 > $Total){echo('class="disabled"');} echo('>
                        <span class="fa fa-3x fa-chevron-circle-right"></span>
                    </a>
                </div>
            </div>
        </div>
    ');

$OUTPUT->footerStart();

include("tool-footer.html");

echo('<script src="scripts/jquery.flip.min.js" type="text/javascript"></script>');
echo('<script src="scripts/cardflipper.js" type="text/javascript"></script>');

$OUTPUT->footerEnd();
