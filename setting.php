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


    $SetID = $_GET["SetID"];

    $rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards_set where SetID=".$SetID);
    foreach ( $rows0 as $row ) {
        $CardSetName = $row["CardSetName"];
        $Active = $row["Active"];
    }

    ?>





    <script>
        function ConfirmDelete()
        {
            return confirm("Are you sure you want to delete? You can't undo this.");
        }

    </script>







    <form  method="post" action="setting_submit.php">

        <table class="table table-bordered" >

            <tr>

                <td style="vertical-align:top">Title</td>

                <td><input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                    <input name="CardSetName" id="CardSetName" style="width:100%;"     Value="<?php echo $CardSetName;?>"/>
                </td>

            </tr>

            <tr>

                <td width="100" style="vertical-align:top">Publish</td>

                <td ><input type="radio" name="Active" value='1' <?php if($Active ==1){ echo "checked='checked'";  } ?> > Publish<br>
                    <input type="radio" name="Active" value='0'      <?php if($Active ==0){ echo "checked='checked'";  } ?> > Unpublish</td>

            </tr>



            <tr>

                <td   colspan="2">

                    <br />







                    <input type="submit" value="     Update     " class="btn btn-primary" >
                    <span style="padding-left:30px;" ></span>
                    <a href="index.php" class="btn btn-primary"> Cancel </a>
                    <span style="padding-left:30px;" ></span>
                    <a href="DeleteCardSet.php?SetID=<?php echo $SetID;?>" class='btn btn-danger' style="float:right;" onclick="return ConfirmDelete();" > Remove </a>

                </td>

            </tr>

        </table>







    </form>
















    <?php

}
else{
    // student area

}


$OUTPUT->footer();


