<?php
require_once "../config.php";
require_once "dao/FlashcardsDAO.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $card = $flashcardsDAO->getCardById($_GET["CardID"]);

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li><a href="index.php">'.$set["CardSetName"].'</a></li>
            <li>Edit Card</li>
        </ul>
    ');

    ?>

    <form method="post" action="actions/EditCard_Submit.php">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Editing Card <?php echo($card["CardNum"]); ?></h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label class="control-label" for="TypeA">Side A Type</label>
                    <select class="form-control" id="TypeA" name="TypeA">
                        <option value="Text" <?php if($card["TypeA"] == "Text"){echo("selected");}?>>Text</option>
                        <option value="Image" <?php if($card["TypeA"] == "Image"){echo("selected");}?>>Image</option>
                        <option value="mp3" <?php if($card["TypeA"] == "mp3"){echo("selected");}?>>Audio (mp3)</option>
                        <option value="Video" <?php if($card["TypeA"] == "Video"){echo("selected");}?>>YouTube / Warpwire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="SideA">Side A</label>
                    <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus required><?php echo($card["SideA"]); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label" for="TypeB">Side B Type</label>
                    <select class="form-control" id="TypeB" name="TypeB">
                        <option value="Text" <?php if($card["TypeB"] == "Text"){echo("selected");}?>>Text</option>
                        <option value="Image" <?php if($card["TypeB"] == "Image"){echo("selected");}?>>Image</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="SideB">Side B</label>
                    <textarea class="form-control" name="SideB" id="SideB" rows="5" required><?php echo($card["SideB"]); ?></textarea>
                </div>

                <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                <input type="hidden" name="CardID" value="<?php echo $_GET["CardID"];?>"/>

                <input type="submit" value="Update Card" class="btn btn-primary">
                <a href="index.php" class="btn btn-danger">Cancel</a>

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
