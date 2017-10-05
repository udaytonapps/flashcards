<?php
require_once('../config.php');

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;
$_SESSION["CourseName"] = $CONTEXT->title;

if ( $USER->instructor ) {

    include("menu.php");
    include("instructor-home.php");

}else{ // student

    $linkId = $LINK->id;
    $shortcut = $PDOX->rowDie("SELECT SetId FROM {$p}flashcards_link WHERE link_id = '".$linkId."';");
    if (isset($shortcut["SetId"])) {
        header( 'Location: '.addSession('playcard.php?SetID='.$shortcut["SetId"].'&CardNum=1&CardNum2=0&Flag=A&Shortcut=1"') ) ;
    } else {
        include("student-home.php");
    }
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();