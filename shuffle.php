<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/namespaces/Tsugi.html

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// Model
$p = $CFG->dbprefix;
$old_code = Settings::linkGet('code', '');



// View
$OUTPUT->header();
$OUTPUT->bodyStart();

$SetID = $_GET["SetID"];
$CardNum2 = $_GET["CardNum2"];
$Flag = $_GET["Flag"];
$UserName = $_SESSION["UserName"];
$FullName = $_SESSION["FullName"];
$Total=0;

include("menu.php");

?>

    <span style="padding-left:30px;"></span><a class='btn btn-primary' href="shuffle0.php?SetID=<?php echo $SetID;?>">Shuffle Cards</a>
    <br><br>

    <link href="styles/FlashCards.css" rel="stylesheet">

<?php

$rows0 = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$SetID);
foreach ( $rows0 as $row ) {
    $Total++;
}


?>



    <center>
        <p style="height:100px;"></p>

        <?php
        $rows = $PDOX->allRowsDie("SELECT * FROM flashcards where SetID=".$SetID." AND CardNum2=".$CardNum2);

        foreach ( $rows as $row ) {

            $CardNum = $row["CardNum"]; // for activity
            if ($Flag =="A"){
                ?>
                <a href="shuffle.php?SetID=<?php echo $SetID;?>&CardNum2=<?php echo $CardNum2;?>&Flag=B" >

                    <table  align="center"  class="SideA">
                        <tr >
                            <td style="text-decoration: none;scroll:auto;"><?php

                                if($row["TypeA"] == "Image"){echo "<img src='".$row["SideA"]."'  style='width:580px;height:370px;'   >";}
                                else if($row["TypeA"] == "mp3"){echo "<audio controls autoplay><source src='".$row["SideA"]."' type='audio/mpeg'>Your browser does not support the audio element.</audio>";}
                                else if($row["TypeA"] == "Video"){
                                    $Warpwire = $row["SideA"];

                                    if (strpos($Warpwire, 'youtube') == true) {
                                        if (strpos($Warpwire, 'embed') == false) {
                                            $Youtube =  $row["SideA"];
                                            $Code = explode("=", $Youtube);
                                            //echo $Code[1]; // piece2
                                            $Warpwire = "https://www.youtube.com/embed/".$Code[1]."?autoplay=1";
                                        }
                                    }


                                    echo "<div class='holder'>";
                                    echo "<iframe src='".$Warpwire."' frameborder='0' style='width:500px;height:330px;float:left;margin-left:50px; margin-top:10px;'></iframe> ";
                                    echo "<div class='bar' ></div></div>";

                                }
                                else {echo $row["SideA"];}

                                ?></td>
                        </tr>
                    </table>

                </a>


                <?php
            }// end of Side A



            if($Flag =="B"){


                $PDOX->queryDie("INSERT INTO flashcards_activity (SetID, CardNum, UserName, FullName) VALUES ( $SetID, $CardNum, '$UserName', '$FullName' )",
                    array(':SetID' => $SetID, ':CardNum' => $CardNum, ':UserName' => $UserName,':FullName' => $FullName)  );


                ?>

                <a href="shuffle.php?SetID=<?php echo $SetID;?>&CardNum2=<?php echo $CardNum2;?>&Flag=A" >


                    <table align="center" class="SideB">
                        <tr><td style="text-decoration: none;scroll:auto;">

                                <?php

                                if($row["TypeB"] == "Image"){echo "<img src='".$row["SideB"]."'  style='width:580px;height:370px;'   >";}
                                else{echo $row["SideB"];}

                                ?>

                            </td></tr>

                    </table>
                </a>

                <?php
            }// end if (side B)

        }// end of loop

        ?>
    </center>


    <div class="center">

        <?php


        if ($Total != 0){ // if total

            $Next = $CardNum2 + 1;
            $Prev = $CardNum2 - 1;




            if ($Prev != 0) { ?>

                <a href="shuffle.php?SetID=<?php echo $SetID;?>&CardNum2=<?php echo $Prev;?>&Flag=A" >

                    <img style="opacity:0.4; z-index:10; float:left;  height:50px; margin-top:-5px;"  src="images/Prev.png" /></a>

            <?php }else{
                echo "<img style='float:left; opacity:0;  height:50px; margin-top:-5px;' src='images/Blank.png' />";
            }
            ?>





            <span><?php echo $CardNum2."/".$Total; ?></span>





            <?php if ($Next < ($Total + 1)) { ?>

                <a href="shuffle.php?SetID=<?php echo $SetID; ?>&CardNum2=<?php echo $Next;?>&Flag=A">

                    <img style="float:right;  opacity:0.4;  z-index:10; height:50px; margin-top:-5px; margin-left:15px;"  src="images/Next.png" /></a>

            <?php }else{
                echo "<img style='float:right;  opacity:0;  z-index:10; height:50px; margin-top:-5px; margin-left:15px;' src='images/Blank.png' />";
            }


        } // if total
        ?>



    </div>



<?php
$OUTPUT->footer();