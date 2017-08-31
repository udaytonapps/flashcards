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

    $rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards where CardID=".$_GET["CardID"]);
    foreach ( $rows0 as $row ) {
        ?>










        <form  method="post" action="EditCard_Submit.php">



            <table class='table table-bordered'>

                <tr>

                    <td style="vertical-align:top; ">Card#</td>

                    <td colspan=2><?php   echo $row["CardNum"]; ?>

                        <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                        <input type="hidden" name="CardID" value="<?php echo $_GET["CardID"];?>"/>

                    </td>

                </tr>

                <tr>
                    <td style="vertical-align:top">Side A</td>


                    <td><textarea name="SideA" id="SideA" cols="45" rows="5" autofocus style="width:100%" required="required" ><?php   echo $row["SideA"]; ?></textarea></td>
                    <td><input type="radio" name="TypeA" value="Text" <?php  if ($row["TypeA"] == "Text"){echo "checked";}?>> Text<br>
                        <input type="radio" name="TypeA" value="Image"  <?php  if ($row["TypeA"] == "Image"){echo "checked";}?>> Image<br>
                        <input type="radio" name="TypeA" value="mp3"  <?php  if ($row["TypeA"] == "mp3"){echo "checked";}?>> mp3<br>
                        <input type="radio" name="TypeA" value="Video"  <?php  if ($row["TypeA"] == "Video"){echo "checked";}?>> Youtube/Warpwire
                    </td>
                </tr>

                <tr>

                    <td width="100" style="vertical-align:top">Side B</td>

                    <td ><textarea name="SideB" id="SideB" cols="45" rows="5" style="width:100%"><?php   echo $row["SideB"]; ?></textarea></td>
                    <td><input type="radio" name="TypeB" value="Text" <?php  if ($row["TypeB"] == "Text"){echo "checked";}?> > Text<br>
                        <input type="radio" name="TypeB" value="Image" <?php  if ($row["TypeB"] == "Image"){echo "checked";}?>> Image</td>
                </tr>

                <tr>

                    <td height="70" colspan="3">

                        <br>

                        <p>

                            <input type="submit" value="Update  Card" class="btn btn-primary" >  <span style="padding-left:30px;" ></span>  <a href="list.php?SetID=<?php echo $_GET["SetID"];?>" class="btn btn-danger"> Cancel </a>

                        </p></td>

                </tr>

            </table>







        </form>
















        <?php
    }// for

}
else{
    // student area

}


$OUTPUT->footer();


