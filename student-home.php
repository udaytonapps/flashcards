<?php

echo('
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Flashcards</a>
            </div>
        </div>
    </nav>
');

$visibleSets = $flashcardsDAO->getAllVisibleSetsForSiteSorted($CONTEXT->id);

if (count($visibleSets) == 0) {
    echo('<p><em>There are currently no available flashcard sets for this course.</em></p>');
} else {

    echo('<div class="row">');

    foreach ( $visibleSets as $set ) {
        $cards = $flashcardsDAO->getCardsInSet($set["SetID"]);
        if (count($cards) > 0) {
            $cardsPile = ' cards-pile';
        } else {
            $cardsPile = '';
        }
        echo('
            <div class="col-sm-4">
                <div class="panel panel-default'.$cardsPile.'">
                    <div class="panel-heading">
                        <span class="label label-success pull-right student-card-count">'.count($cards).' Cards</span>
                        <h3>'.$set["CardSetName"].'</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <a href="PlayCard.php?SetID='.$set["SetID"].'&CardNum=1&CardNum2=0&Flag=A"');if(count($cards) == 0){echo(' class="disabled"');}echo('>
                                    <span class="fa fa-2x fa-th-large"></span>
                                    <br />
                                    <small>Flashcards</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ');
    }

    echo('</div>');
}

