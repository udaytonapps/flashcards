<?php
require_once "../config.php";
require_once('dao/FlashcardsDAO.php');
require_once('util/FlashcardUtils.php');

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
    .img-fit {
        object-fit: scale-down;
        max-width: 100%;
        max-height: 75px;
    }
</style>
<?php

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $cardsInSet = $flashcardsDAO->getCardsInSet($setId);

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $Total = count($cardsInSet);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li>' .$set["CardSetName"].'</li>
        </ul>
        
        <div class="row cardRow">

        <p>
            <a class="btn btn-success" href="AddCard.php?SetID='.$setId.'"><span class="fa fa-plus"></span> Add New Card</a>        
        </p>
        
        <h2>Cards in "'.$set["CardSetName"].'" <span class="badge">'.$Total.' Cards</span></h2>
    ');

    if ($Total == 0) {
        echo('<p><em>There are currently no cards in this set.</em></p>');
    } else {
        usort($cardsInSet, array('FlashcardUtils', 'compareCardNum'));
        $cardNum = 1;
        foreach ( $cardsInSet as $row ) {

            echo('
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading list-card">
                    <h3 style="margin:0;">
                    <div class="pull-right">
                        <a href="EditCard.php?CardID='.$row["CardID"].'&SetID='.$row["SetID"].'">
                        <span class="fa fa-fw fa-pencil" aria-hidden="true"></span>
                        <span class="sr-only">Edit</span>
                        </a>
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
                        <a class="deleteCard" href="actions/DeleteCard.php?CardID='.$row["CardID"].'&SetID='.$row["SetID"].'" onclick="return ConfirmDeleteCard();">
                        <span aria-hidden="true" class="fa fa-fw fa-trash"></span>
                        <span class="sr-only">Delete</span>
                        </a>
        ');
            echo('</div> '.$cardNum.'. </h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-6 sideA">
                            <h5 class="text-muted">Side A</h5>
        ');

            if($row["TypeA"] == "Image" || $row["TypeA"] == "mp3" || $row["TypeA"] == "Video") {
                echo('<a href="'.$row["SideA"].'" target="_blank">'.$row["SideA"].'</a>');
            } else if($row['TypeA'] == 'Text') {
                echo($row["SideA"]);
            } else {
                if($row["MediaA"] != null && $row["SideA"] != null) {
                    ?>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($row["MediaA"], false, true)) ?>" class="img-fit">
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <?php echo $row["SideA"] ?>
                        </div>
                    </div>
                    <?php
                } else if($row["MediaA"] != null) {
                    ?>
                    <div class="row">
                        <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($row["MediaA"], false, true)) ?>" class="img-fit">
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <?php echo $row["SideA"] ?>
                    </div>
                    <?php
                }
            }

            echo('
                        </div>
                        <div class="col-sm-6 sideB">
                            <h5 class="text-muted">Side B</h5>');
            if($row["TypeB"] == "Image") {
                echo('<a href="'.$row["SideB"].'" target="_blank">'.$row["SideB"].'</a>');
            } else if($row['TypeB'] == 'Text') {
                echo($row["SideB"]);
            } else {
                if($row["MediaB"] != null && $row["SideB"] != null) {
                    ?>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($row["MediaB"], false, true)) ?>" class="img-fit">
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <?php echo $row["SideB"] ?>
                        </div>
                    </div>
                    <?php
                } else if($row["MediaB"] != null) {
                    ?>
                    <div class="row">
                        <img src="<?php echo addSession(BlobUtil::getAccessUrlForBlob($row["MediaB"], false, true)) ?>" class="img-fit">
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <?php echo $row["SideB"] ?>
                    </div>
                    <?php
                }
            }

            echo('
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

