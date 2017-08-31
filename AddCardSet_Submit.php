<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;
$old_code = Settings::linkGet('code', '');

$CourseName = Settings::linkGet('CourseName', '');


$UserName=$_SESSION["UserName"];
$CourseName = $_POST["CourseName"];
$CardSetName = $_POST["CardSetName"];

$CardSetName = str_replace("'", "&#39;", $CardSetName);


if ( $USER->instructor ) {

    $PDOX->queryDie("INSERT INTO flashcards_set (UserName, CourseName, CardSetName) VALUES ( '$UserName', '$CourseName', '$CardSetName')",
        array(':UserName' => $UserName, ':CourseName' => $CourseName, ':CardSetName' => $CardSetName)  );
    $_SESSION['success'] = __('New FlashCardSet has been added!');
    header( 'Location: '.addSession('index.php') ) ;


}


$OUTPUT->footer();


