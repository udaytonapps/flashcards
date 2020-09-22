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

    include("menu.php");

    ?>

    <form action="actions/AddCardSet_Submit.php" method="post">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Create New Card Set</h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label class="control-label" for="CardSetName">Card Set Title</label>
                    <input id="CardSetName" name="CardSetName" class="form-control" required/>
                </div>

                <input name="CourseName" id="CourseName" type="hidden" value="<?php echo($CONTEXT->title); ?>"/>

                <input class="btn btn-primary" type="submit" value="Add Card Set" />
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
