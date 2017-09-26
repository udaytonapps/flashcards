<?php

echo('<h2>Card Sets</h2>');

$rows = $PDOX->allRowsDie("select * from {$p}flashcards_set where CourseName='".$_SESSION["CourseName"]."' order by CardSetName;'");

if (count($rows) == 0) {
    echo('<p><em>You currently do not have any card sets in this site. Create a new card set or use the import button below to copy a card set from another site.</em></p>');
}

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
                            <small>'.count($cards).' Cards</small>
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
                                    <a href="playcard.php?SetID='.$row["SetID"].'&CardNum=1&CardNum2=0&Flag=A" ');if(count($cards) == 0){echo('class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-th-large"></span>
                                    <br />
                                    <small>Flashcards</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center" style="border-right: 1px solid #ccc;">
                                    <a href="start.php?SetID='.$row["SetID"].'" ');if(count($cards) < 5){echo('class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-check-square-o"></span>
                                    <br />
                                    <small>Review</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <a href="usage.php?Page=1&SetID='.$row["SetID"].'&CardSetName='.$row["CardSetName"].'" ');if(count($cards) == 0){echo('class="disabled"');}echo('>
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

/* Import from site */

$courses = $PDOX->allRowsDie("SELECT DISTINCT CourseName FROM {$p}flashcards_set where UserName='".$_SESSION["UserName"]."' AND CourseName !='".$_SESSION["CourseName"]."' AND Visible=1");

echo('
    
    <h3><button class="btn btn-primary" data-toggle="collapse" data-target="#import-cards-row">Import Card Set</button></h3>
    
    <div id="import-cards-row" class="row collapse">
');

foreach ( $courses as $course ) {

    $sets = $PDOX->allRowsDie("SELECT CardSetName, SetID FROM {$p}flashcards_set where CourseName='" . $course['CourseName'] . "' order by CardSetName");

    echo('<div id="card-set-list" class="list-group col-md-4">');

    echo('<h4>'.$course["CourseName"].'</h4>');

    foreach ($sets as $set) {

        $cards2 = $PDOX->allRowsDie("select * from {$p}flashcards where SetID=" . $set["SetID"]);

        if (count($cards2) > 0) {
            $countLabel = 'success';
            $textLabel = 'success';
        } else {
            $countLabel = 'default';
            $textLabel = 'muted';
        }

        echo('
            <a href="copy1.php?SetID='.$set["SetID"].'"  onclick="return ConfirmCopyCardSet();" class="list-group-item">
                <div class="list-group-item-heading">
                    <span class="label label-'.$countLabel.' pull-right">'.count($cards2).' Cards</span>
                    <span class="fa-stack small text-'.$textLabel.'">
                        <span class="fa fa-square fa-stack-2x" style="top:-6px;"></span>
                        <span class="fa fa-square-o fa-stack-2x" style="top:2px;left:-8px;"></span>
                        <span class="fa fa-inverse fa-upload fa-stack-1x" style="top:-6px;"></span>
                    </span>
                    <h5 class="card-set-list-name text-'.$textLabel.'">'.$set["CardSetName"].'</h5>                    
                </div>
            </a>
        ');
    }

    echo('</div>');
}

    echo('</div>');