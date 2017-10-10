<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$linkId = $LINK->id;

if ( $USER->instructor ) {

    include("menu.php");

    $allSets = $PDOX->allRowsDie("select * from {$p}flashcards_set where CourseName='".$_SESSION["CourseName"]."' order by CardSetName;");

    $previousLink = $PDOX->rowDie("SELECT * FROM {$p}flashcards_link WHERE link_id ='".$linkId."';");

    echo('<form action="actions/LinkToSet_Submit.php" method="post">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Send Students to a Card Set</h3>
            </div>

            
            
            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label for="CardSet">Link to Card Set</label>
                    <select class="form-control" id="linkToSet" name="linkToSet">');

                        foreach($allSets as $set) {

                            if(isset($previousLink["SetID"]) && $previousLink["SetID"] == $set["SetID"]) {
                                echo('<option value="'.$set["SetID"].'" selected>'.$set["CardSetName"].'</option>');
                            } else {
                                echo('<option value="'.$set["SetID"].'">'.$set["CardSetName"].'</option>');
                            }

                        }

                echo('</select>
                </div>
                <input class="btn btn-primary" type="submit" value="Link to Flashcard Set" /> 
                <a href="actions/UnlinkFromSet_Submit.php" class="btn btn-danger">Unlink</a>
            </div>
        </div>

        </form>');
} else {
    // student so send back to index
    header( 'Location: '.addSession('index.php') ) ;
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();