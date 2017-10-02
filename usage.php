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

    $setId = $_GET["SetID"];

    $cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId.";");
    $totalCards = count($cardsInSet);

    $hasRosters = LTIX::populateRoster(false);

    if ($hasRosters) {

        echo ('
        <div id="usage-table-container">
            <table class="table table-responsive table-bordered" id="usage-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
        ');
            for ($x = 1; $x <= $totalCards; $x++) {
                echo('<th>Card '.$x.'</th>');
            }
        echo('
                    </tr>
                </thead>
                <tbody>
        ');
        $rosterData = $GLOBALS['ROSTER']->data;
        foreach($rosterData as $student) {
            echo('<tr>
                    <td>'.$student["person_name_family"].', '.$student["person_name_given"].'</td>');

            for ($x = 1; $x <= $totalCards; $x++) {
                $completed = $PDOX->rowDie("SELECT * FROM {$p}flashcards_activity WHERE FullName = '".$student["person_name_full"]."' AND SetID = '".$setId."' AND CardNum = '".$x."';");
                if($completed) {
                    echo('<td><span class="fa fa-check"></span></td>');
                } else {
                    echo('<td></td>');
                }
            }

            echo('</tr>');
        }
        echo('</tbody>
            </table>
        </div>
        ');
    }
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

echo('<script src="scripts/jquery.tablesorter.min.js" type="text/javascript"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#usage-table").tablesorter({
                        sortList: [[0,0]]
                    });
                });
            </script>
');

$OUTPUT->footerEnd();
