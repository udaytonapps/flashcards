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

if ( $USER->instructor ) {

    include("menu.php");

    $setId = $_GET["SetID"];

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Card Sets</a></li>
                <li>' .$set["CardSetName"].'</li>
            </ul>
        ');

    $cardsInSet = $flashcardsDAO->getCardsInSet($setId);
    $totalCards = count($cardsInSet);

    $hasRosters = LTIX::populateRoster(false);

    if ($hasRosters) {

        echo('<div class="row">
                  <div class="col-sm-12">
                      <h3><a href="actions/ExportActivity.php?SetID='.$setId.'" class="btn btn-link pull-right">Export Usage to Excel</a><span class="fa fa-bar-chart"></span> '.$set["CardSetName"].' Usage</h3>
                  </div>
              </div>');

        echo('<div class="row"><div class="col-sm-4"><h4>Student Name</h4></div><div class="col-md-6"><h4>Progress</h4></div></div>');

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('FlashcardUtils', 'compareStudentsLastName'));

        foreach($rosterData as $student) {
            // Only want students
            if ($student["role"] == 'Learner') {
                echo('<div class="row">
                    <div class="col-sm-4">'.$student["person_name_family"].', '.$student["person_name_given"].'</div>');

                $numberCompleted = $flashcardsDAO->getNumberOfSeenCards($student["user_id"], $setId);

                $percentComplete = $numberCompleted["Count"] / $totalCards * 100;

                if($percentComplete < 25) {
                    $progressClass = 'danger';
                } else if ($percentComplete < 75) {
                    $progressClass = 'warning';
                } else {
                    $progressClass = 'success';
                }

                echo('<div class="col-sm-6">
                    <div class="progress">
                        <div class="progress-bar progress-bar-'.$progressClass.'" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentComplete.'%">
                            '.$numberCompleted["Count"].' / '.$totalCards.' Cards Viewed
                        </div>
                    </div>
                  </div>
                </div>
            ');
            }
        }

    }
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
