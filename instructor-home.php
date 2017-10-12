<?php

echo('<h2>Card Sets');

$linkId = $LINK->id;

$shortcut = $flashcardsDAO->getShortcutSetIdForLink($linkId);

if (isset($shortcut["SetID"])) {
    $shortcutSet = $flashcardsDAO->getFlashcardSetById($shortcut["SetId"]);
    echo('<br /><small><span class="fa fa-link"></span> This instance of Flashcards is linked to <a href="PlayCard.php?SetID='.$shortcut["SetID"].'&CardNum=1&CardNum2=0&Flag=A">'.$shortcutSet["CardSetName"].'</a>.</small>');
}

echo('</h2>');

$allCardSets = $flashcardsDAO->getAllSetsForSiteSorted($CONTEXT->id);

if (count($allCardSets) == 0) {
    echo('<p><em>You currently do not have any card sets in this site. Create a new card set or use the import button below to copy a card set from another site.</em></p>');
}

echo('<div class="row">');

    foreach ( $allCardSets as $cardSet ) {
        if ($cardSet["Visible"]) {

            if($cardSet["Active"] == 0) {
                $flag = 1;
                $panelClass = 'default';
                $pubAction = 'Unpublished';
            } else {
                $flag = 0;
                $panelClass = 'success';
                $pubAction = 'Published';
            }

            $cards = $flashcardsDAO->getCardsInSet($cardSet["SetID"]);
            if (count($cards) > 0) {
                $cardsPile = ' cards-pile';
            } else {
                $cardsPile = '';
            }
            echo('
                <div class="col-sm-4">
                    <div class="panel panel-'.$panelClass.$cardsPile.'">
                        <div class="panel-heading">
                            <h3>
                                <a href="AllCards.php?SetID='.$cardSet["SetID"].'">
                                    <span class="fa fa-pencil-square-o"></span>
                                    '.$cardSet["CardSetName"].'
                                </a>
                            </h3>
                            <a class="btn btn-'.$panelClass.' pull-right publish-link" href="actions/Publish.php?SetID='.$cardSet["SetID"].'&Flag='.$flag.'">'.$pubAction.'</a>
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
                                <div class="col-xs-6 text-center">
                                    <a href="PlayCard.php?SetID='.$cardSet["SetID"].'&CardNum=1&CardNum2=0&Flag=A" ');if(count($cards) == 0){echo('class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-th-large"></span>
                                    <br />
                                    <small>Flashcards</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <a href="Usage.php?SetID='.$cardSet["SetID"].'" ');if(count($cards) == 0){echo('class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-bar-chart"></span>
                                    <br />
                                    <small>Usage</small>
                                    </a>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <a href="Settings.php?SetID='.$cardSet["SetID"].'">
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

$courses = $flashcardsDAO->getAllSitesForUserWithACardSet($USER->id, $CONTEXT->id);

echo('
    
    <h3><button class="btn btn-primary ');
            if(count($courses)==0) {
                echo('disabled');
            }
    echo('" data-toggle="collapse" data-target="#import-cards-row">Import Card Set</button></h3>
    
    <div id="import-cards-row" class="row collapse">
');

foreach ( $courses as $course ) {

    $sets = $flashcardsDAO->getAllSetsForSiteSorted($course["context_id"]);

    echo('<div id="card-set-list" class="list-group col-md-4">');

    echo('<h4>'.$flashcardsDAO->getCourseNameForId($course["context_id"]).'</h4>');

    foreach ($sets as $set) {

        $cards2 = $flashcardsDAO->getCardsInSet($set["SetID"]);

        if (count($cards2) > 0) {
            $countLabel = 'success';
            $textLabel = 'success';
        } else {
            $countLabel = 'default';
            $textLabel = 'muted';
        }

        echo('
            <a href="actions/ImportCardSet.php?SetID='.$set["SetID"].'"  onclick="return ConfirmCopyCardSet();" class="list-group-item">
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