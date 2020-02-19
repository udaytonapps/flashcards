<?php
require_once "../config.php";
require_once "dao/FlashcardsDAO.php";
require_once "util/FlashcardUtils.php";

use Tsugi\Blob\BlobUtil;
use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

?>
<style>
    .both {
        margin: 5px;
        height: 100%;
    }
    .col-row {
        width: 100%;
        height: 100%;
    }
    .img-fit {
        object-fit: scale-down;
        max-width: 100%;
    }
</style>
<?php

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
                        <div class="play-card text-center">');
        if ($theCard["TypeA"] == "Image") {
            echo('<img src="'.$theCard['SideA'].'">');
        } else if ($theCard["TypeA"] == "Media") {
            if($theCard['MediaA'] != null && $theCard['SideA'] != null) {
                ?>
                <div class="row col-row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="image-container">
                            <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($theCard["MediaA"])) ?>" class="img-fit">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <span><?php echo $theCard["SideA"] ?></span>
                    </div>
                </div>

                <?php
            }  else if($theCard['MediaA'] != null) {
                ?>
                <div class="row">
                    <div class="image-container">
                        <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($theCard["MediaA"])) ?>" class="img-fit">
                    </div>
                </div>

                <?php
            } else {
                echo('<span>'.$theCard["SideA"].'</span>');
            }
        } else if ($theCard["TypeA"] == "Text") {
            echo('<span>'.$theCard["SideA"].'</span>');
        }
        echo('</div>
                        <span class="h3 text-muted"><span class="fa fa-undo"></span> Click to flip</span>
                    </div>
                    <div class="back">
                        <span class="h4 text-muted">Side B</span>
                        <div class="play-card text-center">');
        if ($theCard["TypeB"] == "Image") {
            echo('<img src="'.$theCard["SideB"].'">');
        } else if ($theCard["TypeB"] == "Media") {
            if($theCard['MediaB'] != null && $theCard['SideB'] != null) {
                ?>
                <div class="row col-row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="image-container">
                            <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($theCard["MediaB"])) ?>" class="img-fit">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="both">
                            <span><?php echo $theCard["SideB"] ?></span>
                        </div>
                    </div>
                </div>

                <?php
            } else if($theCard['MediaB'] != null) {
                ?>
                <div class="row">
                    <div class="image-container">
                        <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($theCard["MediaB"])) ?>" class="img-fit">
                    </div>
                </div>

                <?php
            } else {
                echo('<span>'.$theCard["SideB"].'</span>');
            }
        } else if ($theCard["TypeB"] == "Text") {
            echo('<span>'.$theCard["SideB"].'</span>');
        }
        echo('</div>
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

echo('<script src="scripts/jquery.flip.js" type="text/javascript"></script>');
echo('<script src="scripts/cardflipper.js" type="text/javascript"></script>');

$OUTPUT->footerEnd();
