<?php
require_once('../config.php');
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();

// Model
$p = $CFG->dbprefix;
$old_code = Settings::linkGet('code', '');

// View
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

include("menu.php");
/*

 $rows = $PDOX->allRowsDie("SELECT * FROM flashcard where SetID=".$_GET["SetID"]." order by CardNum", array(':LI' => $LINK->id));
 foreach ( $rows as $row ) {
        $CardNum = $row['CardNum'];
 }

*/


if ( $USER->instructor ) {
// instructor area
    ?>





    <form action="AddCardSet_Submit.php" method="post" >

        <table class='table table-bordered'>
            <tr><td valign="top"><strong>Course Name</strong></td> <td valign="top"><?php echo $_SESSION["CourseName"]; ?> <input name="CourseName" type="hidden" value="<?php echo $_SESSION["CourseName"]; ?>" /></td></tr>
            <tr><td width="26%"><strong>Card Set Title </strong></td><td width="74%"><input name="CardSetName" required="required" id="CardSetName" style="width:40%"  /></td></tr>
            <tr><td colspan="2"><br />

                    <p>

                        <input class="btn btn-primary" type="submit" value="     Add This Flashcard Set    " />

                    </p></td>

            </tr>
        </table>

    </form>








    <?php

}
else{
    // student area

}


$OUTPUT->footer();


