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

    $setId = $_GET["SetID"];

    $cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId." order by CardNum;");
    $set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

    $Total = count($cardsInSet);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li>' .$set["CardSetName"].'</li>
        </ul>
        
        <div class="row cardRow">

        <p>
            <a class="btn btn-success" href="AddCard.php?SetID='.$_GET["SetID"].'"><span class="fa fa-plus"></span> Add New Card</a>        
        </p>
        
        <h2>Cards in "'.$set["CardSetName"].'" <span class="badge">'.$Total.' Cards</span></h2>
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no cards in this set.</em></p>');
    } else {
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
                            <a href="actions/Move.php?CardID=' . $row["CardID"] . '&CardNum=' . $row["CardNum"] . '&SetID=' . $_GET["SetID"] . '&Flag=1">
                                <span class="fa fa-chevron-circle-up"></span>
                            </a>
                ');
            }
            if($cardNum != $Total) {
                echo('
                            <a href="actions/Move.php?CardID=' . $row["CardID"] . '&CardNum=' . $row["CardNum"] . '&SetID=' . $_GET["SetID"] . '&Flag=0">
                                <span class="fa fa-chevron-circle-down"></span>
                            </a>
                ');
            }
            echo('
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-6 sideA">
                            <h4>Side A</h4>
        ');

            if($row["TypeA"] == "Image" || $row["TypeA"] == "mp3" || $row["TypeA"] == "Video") {
                echo('<a href="'.$row["SideA"].'" target="_blank">'.$row["SideA"].'</a>');
            } else {
                echo($row["SideA"]);
            }

            echo('
                        </div>
                        <div class="col-sm-6 sideB">
                            <h4>Side B</h4>
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

