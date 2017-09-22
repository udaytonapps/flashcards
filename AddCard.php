<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData();

// Model
$p = $CFG->dbprefix;

// View
$OUTPUT->header();
$OUTPUT->bodyStart();

include("menu.php");

echo "<br><br><br>";

if ( $USER->instructor ) {
// instructor area
    $Total=0;
    $rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_GET["SetID"]." order by CardNum");
    foreach ( $rows0 as $row ) {
        $Total++;
    }
    $Next = $Total+1;

    ?>



    <form  method="post" action="AddCard_Submit.php">



        <table class='table table-bordered' width="800" >







            <tr>

                <td style="vertical-align:top; ">Card#</td>

                <td colspan=2><?php echo $Next; ?>

                    <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                    <input  type="hidden" name="CardNum" value="<?php echo $Next; ?>"/>

                </td>

            </tr>

            <tr>

                <td style="vertical-align:top">Side A</td>

                <td><textarea name="SideA" id="SideA" cols="45" rows="5" autofocus style="width:100%" required="required" ></textarea></td>
                <td><input type="radio" name="TypeA" value="Text" checked > Text<br>
                    <input type="radio" name="TypeA" value="Image"> Image<br>
                    <input type="radio" name="TypeA" value="mp3" > mp3<br>
                    <input type="radio" name="TypeA" value="Video"  > Youtube/Warpwire</td>
            </tr>

            <tr>

                <td width="100" style="vertical-align:top">Side B</td>

                <td ><textarea name="SideB" id="SideB" cols="45" rows="5" style="width:100%"></textarea></td>
                <td><input type="radio" name="TypeB" value="Text" checked > Text<br>
                    <input type="radio" name="TypeB" value="Image"> Image</td>
            </tr>

            <tr>

                <td height="78" colspan="3">

                    <br />




                    <p>

                        <input type="submit" value="Add This Card" class="btn btn-primary" >  <span style="padding-left:30px;" ></span>  <a href="list.php?SetID=<?php echo $_GET["SetID"];?>" class="btn btn-danger"> Cancel </a>

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


