<?php
require_once "../config.php";
require_once "dao/FlashcardsDAO.php";
require_once "util/FlashcardUtils.php";

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

    $cardsInSet = $flashcardsDAO->getCardsInSet($setId);

    usort($cardsInSet, array('FlashcardUtils', 'compareCardNum'));

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $Total = count($cardsInSet);
    $Next = $Total + 1;

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li><a href="index.php">'.$set["CardSetName"].'</a></li>
            <li>Add New Card</li>
        </ul>
    ');

    ?>

    <form method="post" action="actions/AddCard_Submit.php">

        <div class="row">
            <div class="col-sm-offset-1 col-sm-8">
                <h3>Adding Card <?php echo($Next); ?></h3>
            </div>

            <div class="col-sm-offset-1 col-sm-8">
                <div class="form-group">
                    <label class="control-label" for="TypeA">Side A Type</label>
                    <select class="form-control" id="TypeA" name="TypeA">
                        <option value="Text" selected>Text</option>
                        <option value="Image">Image</option>
                        <option value="mp3">Audio (mp3)</option>
                        <option value="Video">YouTube / Warpwire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="SideA">Side A</label>
                    <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus required></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label" for="TypeB">Side B Type</label>
                    <select class="form-control" id="TypeB" name="TypeB">
                        <option value="Text" selected>Text</option>
                        <option value="Image">Image</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label" for="SideB">Side B</label>
                    <textarea class="form-control" name="SideB" id="SideB" rows="5" required></textarea>
                </div>

                <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                <input  type="hidden" name="CardNum" value="<?php echo $Next; ?>"/>

                <input type="submit" value="Add Card" class="btn btn-primary">
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
