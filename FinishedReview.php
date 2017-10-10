<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

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

$set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

if ($shortCut == 0) {
    echo('
            <ul class="breadcrumb">
                <li><a href="index.php">All Card Sets</a></li>
                <li>' .$set["CardSetName"].'</li>
            </ul>
        ');
}

echo('<div class="container">
            <div class="jumbotron">
                <h2>Congratulations!</h2>
                <p>You\'ve marked all of the cards in this set as "known." Use the options below to reset all of the cards and keep reviewing or return to study mode.</p>
                <input type="hidden" id="sess" value="'.$_GET["PHPSESSID"].'">
            </div>
            <div class="col-xs-6 text-center">
                <h2><a id="study-cards" href="PlayCard.php?SetID='.$setId.'&CardNum=1&CardNum2=0&Flag=A&Shortcut='.$shortCut.'"><span class="fa fa-th-large"></span> Study</a></h2>            
            </div>
            <div class="col-xs-6 text-center">
                <h2><a id="reset-cards" href="actions/ResetKnownCards_Submit.php?ReviewMode=1&Shortcut='.$shortCut.'"><span class="fa fa-refresh"></span> Reset</a></h2>            
            </div>
      </div>
     ');


$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
