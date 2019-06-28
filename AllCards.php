<?php
require_once "../config.php";
require_once('dao/FlashcardsDAO.php');
require_once('util/FlashcardUtils.php');

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $linkId = $LINK->id;

    $linkSet = $flashcardsDAO->getShortcutSetIdForLink($linkId);

    $setId = $linkSet["SetID"];

    $cardsInSet = $flashcardsDAO->getCardsInSet($setId);

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $Total = count($cardsInSet);


    if($set["Active"] == 0) {
        $flag = 1;
        $panelClass = 'default';
        $pubAction = 'Unpublished';
    } else {
        $flag = 0;
        $panelClass = 'success';
        $pubAction = 'Published';
    }

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li>' .$set["CardSetName"].'</li>
        </ul>
       
        </div>
        
        
        
        
        <div class="row cardRow">
            <div class="col-md-8">
                <p>
                    <a class="btn btn-success" href="AddCard.php?SetID='.$setId.'"><span class="fa fa-plus"></span> Add New Card</a>        
                </p>
                
                <h2>Cards in "'.$set["CardSetName"].'" <span class="badge">'.$Total.' Cards</span></h2>
            </div>
        <div class="col-md-1 text-center">
            <a href="PlayCard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A" ');if($Total == 0){echo('class="disabled"');}echo('>
                <span class="fa fa-2x fa-th-large"></span>
                <br />
                <small>Student View</small>
            </a>
        </div>
        <div class="col-md-1 text-center">
            <a href="Usage.php?SetID='.$set["SetID"].'" ');if($Total == 0){echo('class="disabled"');}echo('>
                <span class="fa fa-2x fa-bar-chart"></span>
                <br />
                <small>Usage</small>
            </a>
        </div>
        <div class="col-md-1 text-center">
            <a href="Settings.php?SetID='.$set["SetID"].'">
                <span class="fa fa-2x fa-cog"></span>
                <br />
                <small>Settings</small>
            </a>
        </div>
        <div class="col-md-1">
            <a class="btn btn-'.$panelClass.' pull-right publish-link" href="actions/Publish.php?SetID='.$set["SetID"].'&Flag='.$flag.'">'.$pubAction.'</a>
        </div>
        </div>
        <div class="row cardRow">
        
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no cards in this set.</em></p>');
    } else {
        usort($cardsInSet, array('FlashcardUtils', 'compareCardNum'));
        $cardNum = 1;
        foreach ( $cardsInSet as $row ) {

            echo('
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading list-card">
                        <a class="btn btn-danger pull-right deleteCard" href="actions/DeleteCard.php?CardID='.$row["CardID"].'&SetID='.$row["SetID"].'" onclick="return ConfirmDeleteCard();"><span class="fa fa-trash-o"></span></a>
                        <a class="btn btn-primary pull-right" href="EditCard.php?CardID='.$row["CardID"].'&SetID='.$row["SetID"].'">Edit</a>
                        <h3 class="card-order">
                            '.$cardNum.'. 
        ');
            if($cardNum != 1) {
                echo('
                            <a href="actions/Move.php?CardID=' . $row["CardID"] . '&CardNum=' . $row["CardNum"] . '&SetID=' . $setId . '&Flag=1">
                                <span class="fa fa-chevron-circle-up"></span>
                            </a>
                ');
            }
            if($cardNum != $Total) {
                echo('
                            <a href="actions/Move.php?CardID=' . $row["CardID"] . '&CardNum=' . $row["CardNum"] . '&SetID=' . $setId . '&Flag=0">
                                <span class="fa fa-chevron-circle-down"></span>
                            </a>
                ');
            }
            echo('
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-6 sideA">
                            <h5 class="text-muted">Side A</h5>
        ');

            if($row["TypeA"] == "Image" || $row["TypeA"] == "mp3" || $row["TypeA"] == "Video") {
                echo('<a href="'.$row["SideA"].'" target="_blank">'.$row["SideA"].'</a>');
            } else {
                echo($row["SideA"]);
            }

            echo('
                        </div>
                        <div class="col-sm-6 sideB">
                            <h5 class="text-muted">Side B</h5>
                            '.$row["SideB"].'
                        </div>
                    </div>
                </div>
            </div>

        ');
            /* Make sure only two cards per row */
            if ($cardNum % 2 == 0) {
                echo('</div><div class="row cardRow">');
            }
            $cardNum++;
        }
    }
    echo('</div>');
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();

