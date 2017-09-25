<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId." order by CardNum;");
    $set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");

    $Total = count($cardsInSet);

    $CardSetName = $set["CardSetName"];
    $Active = $set["Active"];

    include("menu.php");

    ?>

    <form  method="post" action="setting_submit.php">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Edit Card Set</h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">

                <div class="form-group">
                    <label class="control-label" for="CardSetName">Card Set Title</label>
                    <input id="CardSetName" name="CardSetName" class="form-control" value="<?php echo($CardSetName); ?>" required/>
                </div>

                <div class="form-group">
                    <div class="radio">
                        <label><input type="radio" name="Active" value="1" <?php if($Active==1){echo('checked="checked"');}?>"/>Publish</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="Active" value="0" <?php if($Active==0){echo('checked="checked"');}?>"/>Unpublish</label>
                    </div>
                </div>

                <input type="hidden" id="SetID" name="SetID" value="<?php echo $_GET["SetID"];?>"/>

                <input class="btn btn-primary" type="submit" value="Update Flashcard Set" />
                <a href="index.php" class="btn btn-danger">Cancel</a>
                <a href="DeleteCardSet.php?SetID=<?php echo($setId); ?>" class="btn btn-danger pull-right" onclick="return ConfirmDeleteCardSet();"><span class="fa fa-trash-o"></span> Delete</a>
            </div>
        </div>
    </form>

    <?php

} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
