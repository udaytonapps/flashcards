<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

// Comparator for student last name used for sorting roster
function compareStudentsLastName($a, $b) {
    return strcmp($a["person_name_family"], $b["person_name_family"]);
}

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    include("menu.php");

    $setId = $_GET["SetID"];

    $set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

    echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Card Sets</a></li>
                <li>' .$set["CardSetName"].'</li>
            </ul>
        ');

    $cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId.";");
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

        usort($rosterData, "compareStudentsLastName");

        foreach($rosterData as $student) {
            // Only want students
            if ($student["role"] == 'Learner') {
                echo('<div class="row">
                    <div class="col-sm-4">'.$student["person_name_family"].', '.$student["person_name_given"].'</div>');

                $numberCompleted = $PDOX->rowDie("SELECT count(distinct(CardNum)) as Count FROM {$p}flashcards_activity WHERE FullName = '".$student["person_name_full"]."' AND SetID = '".$setId."';");

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
