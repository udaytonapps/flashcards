<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/namespaces/Tsugi.html

use \Tsugi\Core\LTIX;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

?>


<?php

$_SESSION["SetID"] = $_GET["SetID"];
$Total=0;
$rows7 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_SESSION["SetID"]);
foreach ( $rows7 as $row ) {
    $Total++;
}


$_SESSION["Total"] = $Total;

if ($Total < 5){echo "<h3>You need to have at least 5 cards.</h3>";}
else{

    header( 'Location: '.addSession('reviewcard.php') ) ;

}


?>