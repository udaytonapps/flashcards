<?php

echo('<h2>Card Sets</h2>');

$rows = $PDOX->allRowsDie("select * from {$p}flashcards_set where CourseName='".$_SESSION["CourseName"]."'");

echo('<div class="row">');

    foreach ( $rows as $row ) {
        if ($row["Visible"]) {

            if($row["Active"] == 0) {
                $flag = 1;
                $panelClass = 'default';
                $pubAction = 'Unpublished';
            } else {
                $flag = 0;
                $panelClass = 'success';
                $pubAction = 'Published';
            }

            $cards = $PDOX->allRowsDie("select * from {$p}flashcards where SetID=" . $row["SetID"]);
            if (count($cards) > 0) {
                $cardsPile = ' cards-pile';
            } else {
                $cardsPile = '';
            }
            echo('
                <div class="col-md-4">
                    <div class="panel panel-'.$panelClass.$cardsPile.'">
                        <div class="panel-heading">
                            <a class="btn btn-'.$panelClass.' pull-right" href="publish.php?SetID='.$row["SetID"].'&Flag='.$flag.'">'.$pubAction.'</a>
                            <h3>
                                <a href="list.php?SetID='.$row["SetID"].'">
                                    <span class="fa fa-pencil-square-o"></span>
                                    '.$row["CardSetName"].'
                                </a>
                            </h3>
                            <small>' . count($cards) . ' Cards</small>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6 text-center">
                                    <h4>Student View</h4>
                                </div>
                                <div class="col-xs-6 text-center">
                                    <h4>Options</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 text-center">
                                    <a href="playcard.php?SetID='.$row["SetID"].'&CardNum=1&Flag=A">
                                    <span class="fa fa-2x fa-th-large"></span>
                                    <br />
                                    <small>Flashcards</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center" style="border-right: 1px solid #ccc;">
                                    <a href="start.php?SetID='.$row["SetID"].'">
                                    <span class="fa fa-2x fa-check-square-o"></span>
                                    <br />
                                    <small>Review</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <a href="usage.php?Page=1&SetID='.$row["SetID"].'&CardSetName='.$row["CardSetName"].'">
                                    <span class="fa fa-2x fa-bar-chart"></span>
                                    <br />
                                    <small>Usage</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <a href="setting.php?SetID='.$row["SetID"].'">
                                    <span class="fa fa-2x fa-cog"></span>
                                    <br />
                                    <small>Settings</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ');
        }
    }

echo('</div>');


//--------------------------------------------------------------MY Flashcards-------------------------------------------//

$rows2 = $PDOX->allRowsDie("SELECT DISTINCT CourseName FROM flashcards_set where UserName='".$_SESSION["UserName"]."' AND CourseName !='".$_SESSION["CourseName"]."' AND Visible=1");

echo "<h3>My Card Sets</h3><br>";
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

