<?php
require_once "../config.php";
require_once "dao/FlashcardsDAO.php";
require_once "util/FlashcardUtils.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

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

$_SESSION["Shortcut"] = $shortCut;

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

if(isset($_GET["ReviewMode"])){
    $isReviewMode = $_GET["ReviewMode"];
} else {
    $isReviewMode = 0;
}

$setId = $_GET["SetID"];
$_SESSION["SetID"] = $setId;

$cardsInSet = $flashcardsDAO->getCardsInSet($setId);

if($CardNum == 0){
    $theCard = $flashcardsDAO->getCardBySetAndNumber2($setId, $CardNum2);
} else {
    $theCard = $flashcardsDAO->getCardBySetAndNumber($setId, $CardNum);
}

$cardKnown = $flashcardsDAO->cardKnown($USER->id, $setId, $theCard["CardID"]);

if($isReviewMode == 1) {

    if($cardKnown && $CardNum == 0) {
        header( 'Location: '.addSession('PlayCard.php?CardNum=0&CardNum2='.++$CardNum2.'&Flag=A&SetID='.$setId.'&Shortcut='.$shortCut.'&ReviewMode='.$isReviewMode) ) ;
    } else if ($cardKnown && $CardNum2 == 0) {
        header( 'Location: '.addSession('PlayCard.php?CardNum='.++$CardNum.'&CardNum2=0&Flag=A&SetID='.$setId.'&Shortcut='.$shortCut.'&ReviewMode='.$isReviewMode) ) ;
    }

    $knownCards = $flashcardsDAO->getKnownCards($USER->id, $setId);

    $knownCardIds = array();

    $x = 0;
    foreach($knownCards as $knownCard) {
        $knownCardIds[$x] = $knownCard["CardID"];
        $x++;
    }

    foreach($cardsInSet as $key => $card) {
        if (in_array($card["CardID"], $knownCardIds)){
            unset($cardsInSet[$key]);
        }
    }
}

$Total = count($cardsInSet);

if($isReviewMode == 1 && $Total == 0) {
    header( 'Location: '.addSession('FinishedReview.php?SetID='.$setId.'&Shortcut='.$shortCut) ) ;
}

$set = $flashcardsDAO->getFlashcardSetById($setId);

if ($CardNum == 0) {
    // We are in "shuffle" mode

    usort($cardsInSet, array('FlashcardUtils', 'compareCardNum2'));

    $Next = 0;
    $Prev = 0;

    reset($cardsInSet);
    $position = 1;
    $Prev2 = 0;
    $Next2 = 0;
    for ($x = 1; $x <= $Total; $x++) {
        if (current($cardsInSet)["CardNum2"] == $CardNum2) {
            $position = $x;

            if ($x > 1) {
                $Prev2 = prev($cardsInSet)["CardNum2"];
                // Set back to current
                next($cardsInSet);
            }

            if ($x < $Total) {
                $Next2 = next($cardsInSet)["CardNum2"];
            }

            break;
        } else {
            next($cardsInSet);
        }
    }

    $percentComplete = $position / $Total * 100;
} else {

    usort($cardsInSet, array('FlashcardUtils', 'compareCardNum'));

    reset($cardsInSet);
    $position = 1;
    $Prev = 0;
    $Next = 0;
    for ($x = 1; $x <= $Total; $x++) {
        if (current($cardsInSet)["CardNum"] == $CardNum) {
            $position = $x;

            if ($x > 1) {
                $Prev = prev($cardsInSet)["CardNum"];
                // Set back to current
                next($cardsInSet);
            }

            if ($x < $Total) {
                $Next = next($cardsInSet)["CardNum"];
            }

            break;
        } else {
            next($cardsInSet);
        }
    }

    $Next2 = 0;
    $Prev2 = 0;

    $percentComplete = $position / $Total * 100;
}

$_SESSION["CardID"] = $theCard["CardID"];

    if ($shortCut == 0) {
        echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Card Sets</a></li>
                <li>' .$set["CardSetName"].'</li>
            </ul>
        ');
    }

    echo('
        <div class="row cardRow">
            <div class="col-sm-3 play-menu">
                <h3>'.$set["CardSetName"].'</h3>
                <span>Progress <strong>'.$position.'/'.$Total.'</strong></span>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$percentComplete.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentComplete.'%">
                        <span class="sr-only">'.$percentComplete.'% Complete</span>
                    </div>
                </div>
                <p>
                    <a class="btn btn-primary" href="actions/Shuffle.php?SetID='.$set["SetID"].'&Shortcut='.$shortCut.'&ReviewMode='.$isReviewMode.'"><span class="fa fa-random"></span> Shuffle Cards</a>                
                </p>
                <div class="review-mode">');

                if($isReviewMode == 1) {
                    echo('<a class="btn btn-success" href="PlayCard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A&ReviewMode=0&Shortcut='.$shortCut.'"><span class="fa fa-check-square-o"></span> Review Mode</a>');
                } else {
                    echo('<a class="btn btn-default" href="PlayCard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A&ReviewMode=1&Shortcut='.$shortCut.'"><span class="fa fa-square-o"></span> Review Mode</a>');
                }

                echo('<br /><a id="reset-cards" href="actions/ResetKnownCards_Submit.php?ReviewMode='.$isReviewMode.'&Shortcut='.$shortCut.'"><span class="fa fa-refresh"></span> Reset Cards</a>
                      <p id="review-mode-info"><em>You can toggle "Review Mode" on or off to see only cards that you don\'t already know. Use the "Reset Cards" button to see all of the cards in Review Mode.</em></p>
                </div>
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
                    <a id="prev-link" href="PlayCard.php?SetID='.$setId.'&CardNum='.$Prev.'&CardNum2='.$Prev2.'&Flag=A&Shortcut='.$shortCut.'&ReviewMode='.$isReviewMode.'" ');if($Prev == 0 && $Prev2 == 0){echo('class="disabled"');} echo('>
                        <span class="fa fa-3x fa-chevron-circle-left"></span>
                    </a>
                    <a href="javascript:void(0);" id="flip-link">
                        <span class="fa fa-3x fa-undo"></span>
                    </a>
                    <a id="next-link" href="PlayCard.php?SetID='.$setId.'&CardNum='.$Next.'&CardNum2='.$Next2.'&Flag=A&Shortcut='.$shortCut.'&ReviewMode='.$isReviewMode.'" ');if(!$Next && !$Next2){echo('class="disabled"');} echo('>
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
