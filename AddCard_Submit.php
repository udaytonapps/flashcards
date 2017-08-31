<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();


// Model
$p = $CFG->dbprefix;

/*

 $rows = $PDOX->allRowsDie("SELECT * FROM flashcard where SetID=".$_GET["SetID"]." order by CardNum", array(':LI' => $LINK->id));
 foreach ( $rows as $row ) {
        $CardNum = $row['CardNum'];
 }


$PDOX->queryDie("INSERT INTO {$p}attend
            (link_id, user_id, ipaddr, attend, updated_at)
            VALUES ( :LI, :UI, :IP, NOW(), NOW() )
            ON DUPLICATE KEY UPDATE updated_at = NOW()",
            array(
                ':LI' => $LINK->id,
                ':UI' => $USER->id,
                ':IP' => $_SERVER["REMOTE_ADDR"]
            )

*/

$TypeA = $_POST["TypeA"];
$TypeB = $_POST["TypeB"];

$SetID=$_POST["SetID"];
$SideA = $_POST["SideA"];
$SideB = $_POST["SideB"];
$SideA = str_replace("'", "&#39;", $SideA);
$SideB = str_replace("'", "&#39;", $SideB);
$CardNum = $_POST["CardNum"];
$Next = $CardNum +1;

//$SideA = str_replace("'", "&#39;", $SideA);



if ( $USER->instructor ) {

    $PDOX->queryDie("INSERT INTO flashcards (SetID, CardNum, SideA, SideB, TypeA, TypeB) VALUES ( $SetID, $CardNum, '$SideA', '$SideB', '$TypeA', '$TypeB' )",
        array(':SetID' => $SetID, ':CardNum' => $CardNum, ':SideA' => $SideA,':SideB' => $SideB,':TypeA' => $TypeA,':TypeB' => $TypeB)  );
    // $_SESSION['success'] = __('New Card has been added!');
    header( 'Location: '.addSession('list.php?SetID='.$SetID) ) ;


}


$OUTPUT->footer();


