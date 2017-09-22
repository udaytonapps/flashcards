<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/namespaces/Tsugi.html

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// View
$OUTPUT->header();
$OUTPUT->bodyStart();
include("menu.php");

// We could use the settings form - but we will keep this simple
?>

    <link href="FlashCards.css" rel="stylesheet">

<?php

$SetID = $_SESSION["SetID"];
$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];

$Total7=0;
$CardSetA = array();
$CardSetB = array();
$CardNumSet=array();

$rows7 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$_SESSION["SetID"]. " order by RAND() limit 4");
foreach ( $rows7 as $row ) {
    $Total7++;
    array_push($CardSetA, $row["SideA"]);
    array_push($CardSetB, $row["SideB"]);
    array_push($CardNumSet, $row["CardNum"]);
}


?>

    <br><br>

    <div style="width:400px; height:300px; margin-top:20px;">
        <table class="Review" >
            <tr height="250">
                <td valign="middle">

                    <?php  $num=rand(0, 3);
                    $_SESSION["SideA"] = $CardSetA[$num];
                    $CardNum = $CardNumSet[$num];
                    // echo $CardNumSet[$num].". ";
                    echo $CardSetA[$num];
                    ?>
                </td>
            </tr>
        </table>

    </div>



    <div style="margin-left:410px; margin-top:-300px; width:600px; height:auto; ">
        <form  method="get" action="reviewcard_submit.php">


            <?php
            for ($x = 0; $x <= 3; $x++) {
                $variable = "A".$x;
                $Option = "B".$x;
                $_SESSION[$variable] = $CardSetB[$x];
                $_SESSION[$Option] = $CardNumSet[$x];


                ?>

                <label>

                    <input type="radio" value="<?php echo $CardNumSet[$x]; ?>" name="Answer" > <span style="margin-left:50px; "><?php   echo $CardSetB[$x];?></span>
                </label>


                <?php
            }// for loop
            ?>

            <input  type="hidden" name="CardNum" value="<?php echo $CardNum; ?>"/>


            <input type="submit" class="btn btn-success" style="padding:10px;margin-top:30px;margin-left:50px;" value="Check Your Answer" />

        </form>

    </div>


<?php
$OUTPUT->footer();