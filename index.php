<?php

require_once "../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();

$p = $CFG->dbprefix;

$displayname = $USER->displayname;

// Start of the output
$OUTPUT->header();
?>
    <!-- Our main css file that overrides default Tsugi styling -->
    <link rel="stylesheet" type="text/css" href="styles/main.css">

    <script>
        function ConfirmCopy()
        {
            return confirm("Are you sure you want to copy this set to this course?");
        }

    </script>
<?php
$OUTPUT->bodyStart();

$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;
$_SESSION["CourseName"] = $CONTEXT->title;



include("menu.php");

if ( $USER->instructor ) {

    ?>



    <span style="padding-left:20px;"></span><a class='btn btn-primary' href="AddCardSet.php">Create New Cardset</a>
    <br><br><br>

    <?php


    $rows = $PDOX->allRowsDie("SELECT * FROM flashcards_set where CourseName='".$_SESSION["CourseName"]."' order by Active DESC");

    echo "<h3>".$_SESSION["CourseName"]."  Flashcards</h3><br>";

    echo "<table class='table table-bordered table-hover'  style='width:800px;'>";
    echo "<tr><th class='heading'> Title </th><th>List ( # )</th><th colspan='2'>Student View</th><th >Usage</th><th>Setting</th><th>Published</th></tr>";

    foreach ( $rows as $row ) {

        if($row["Visible"]){

            $Total=0;
            if($row["Active"] == 0){$BG="#b7bdbf";}else{$BG="#ffffff";}
            echo "<tr bgcolor='".$BG."' >";
            echo "<td><b>".$row["CardSetName"]."</b></td>";


            $rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$row["SetID"]);

            foreach ( $rows0 as $row0 ) {
                $Total++;
            }




            echo "<td class='c1'> <a  href='list.php?SetID=".$row["SetID"]."'> List (".$Total.") </a></td>";

            echo "<td class='c1'> <a href='playcard.php?SetID=".$row["SetID"]."&CardNum=1&Flag=A'> Flashcards</a></td>";
            echo "<td class='c1'> <a href='start.php?SetID=".$row["SetID"]."'> Review</a></td>";

            echo "<td class='c1'> <a href='usage.php?Page=1&SetID=".$row["SetID"]."&PHPSESSID=".$_GET["PHPSESSID"]."&CardSetName=".$row["CardSetName"]."'> Usage</a></td>";
            echo "<td class='c1'> <a href='setting.php?SetID=".$row["SetID"]."'> Setting</a></td>";

            //echo "<td>".$P."</td><td width='100'>";
            echo "<td class='c1'>";

            if($row["Active"] ==0){ echo "<a  class='btn btn-primary' href='publish.php?SetID=".$row["SetID"]."&Flag=1'>Publish</a>";}
            else{ echo "<a class='btn btn-primary' href='publish.php?SetID=".$row["SetID"]."&Flag=0'>Unpublish</a>";}
            echo "</td>";

            echo "</tr>\n";
        }// if
    }// for

    echo("</table><br>");



//--------------------------------------------------------------MY Flashcards-------------------------------------------//

    $rows2 = $PDOX->allRowsDie("SELECT DISTINCT CourseName FROM flashcards_set where UserName='".$_SESSION["UserName"]."' AND CourseName !='".$_SESSION["CourseName"]."' AND Visible=1");

    echo "<h3>My Flashcards</h3><br>";
    echo "<table class='table table-bordered'  cellpadding='10' style='width:800px;'>";
    echo "<tr><th> Course Name </th><th> Title</th></tr>";

    foreach ( $rows2 as $row ) {


        echo "<tr><td width='200'><b>";
        echo $row['CourseName'];



        $rows0 = $PDOX->allRowsDie("SELECT CardSetName, SetID FROM flashcards_set where CourseName='".$row['CourseName']."'");

        echo "</b></td><td>	
				
				
				<table style='width:600px;' class='table table-bordered'>
				";
        foreach ( $rows0 as $row ) {


            echo "<tr><td><a style='min-width:200px;' href='list.php?SetID=".$row['SetID']."'>   ".$row["CardSetName"]."</a>";
            echo "<a class='btn btn-primary' style='float:right;' href='copy1.php?SetID=".$row['SetID']."'  onclick= 'return ConfirmCopy();'>  Copy to this course</a></td></tr>";
        }

        echo "</table>
	
	
	</td></tr>";



    }


    echo("</table><br><br>");












}else{ // student

    ?>
    <br><br>
    <?php


    $rows = $PDOX->allRowsDie("SELECT * FROM flashcards_set where CourseName='".$_SESSION["CourseName"]."' AND Active=1 AND Visible=1");
    echo "<h3>Welcome, ".$_SESSION["FullName"]."</h3><br>";
    echo "<table class='table table-bordered table-hover'  width='600' cellpadding='10'";
    echo "<tr><th>Flashcards</th><th>  </th><th></th></tr>";

    foreach ( $rows as $row ) {
        echo "<tr>";
        echo "<td>".$row["CardSetName"]."</td>";
        echo "<td> <a href='playcard.php?SetID=".$row["SetID"]."&CardNum=1&Flag=A'>Study</a></td>";
        echo "<td> <a href='start.php?SetID=".$row["SetID"]."&CardNum=1&Flag=A'>Review</a></td>";

        echo "</tr>\n";
    }
    echo("</table>\n");

}

$OUTPUT->footerStart();
?>
    <!-- Our main javascript file for tool functions -->
    <script src="scripts/main.js" type="text/javascript"></script>
<?php
$OUTPUT->footerEnd();