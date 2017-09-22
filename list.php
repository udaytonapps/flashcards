<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// Model
$p = $CFG->dbprefix;

// View
$OUTPUT->header();
$OUTPUT->bodyStart();

$Total = 0;

$rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_GET["SetID"]);
foreach ( $rows0 as $row ) {
    $Total++;
}




include("menu.php");

if ( $USER->instructor ) {
    ?>



    <script>
        function ConfirmDelete()
        {
            return confirm("Are you sure you want to delete? You can't undo this.");
        }

    </script>



    <ul class="breadcrumb">
        <li><a href="index.php">All Card Sets</a></li>
        <li></li>
    </ul>


    <span style="padding-left:20px;"></span><a class='btn btn-primary' href="AddCard.php?SetID=<?php echo $_GET["SetID"];?>">Add New Card</a>
    <br><br><br><br>



    <?php

    $rows = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_GET["SetID"]." order by CardNum");

    $rowNum=0;


    echo('<table class="table table-bordered table-hover"  width=800>'."\n");
    echo("<tr><th> #</th><th></th><th>Side A </th><th> Side B</th><th>Edit</th><th>Remove</th></tr>");
    foreach ( $rows as $row ) {


        $rowNum++;

        echo "<tr><td width='50'>".$row['CardNum']."</td>";



        echo "<td width='50'>";

        if($rowNum != 1){echo "<a  href='move.php?CardID=".$row["CardID"]."&CardNum=".$row["CardNum"]."&SetID=".$_GET["SetID"]."&Flag=1' ><span class='glyphicon glyphicon-arrow-up' ></span></a>"; }
        if($rowNum != $Total){
            echo "<a style='float:right;' href='move.php?CardID=".$row["CardID"]."&CardNum=".$row["CardNum"]."&SetID=".$_GET["SetID"]."&Flag=0' ><span class='glyphicon glyphicon-arrow-down' ></span></a>"; }
        echo("</td><td>".$row['SideA']."</td>");
        echo("</td><td> ");
        echo($row['SideB']);
        echo("</td><td width='100'><a class='btn btn-primary' href='EditCard.php?CardID=".$row["CardID"]."&SetID=".$row["SetID"]."' > Edit</a></td>");
        echo("<td width='100'><a class='btn btn-danger' href='DeleteCard.php?CardID=".$row["CardID"]."&SetID=".$row["SetID"]."' onclick= 'return ConfirmDelete();'> Remove</a></td>");
        echo("</tr>");
    }
    echo("</table>\n");


    echo "<br><br>";
}


$OUTPUT->footer();


