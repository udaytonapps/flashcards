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
            <li><a href="AllCards.php?SetID=' .$setId.'">'.$set["CardSetName"].'</a></li>
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
                    <label class="control-label" for="SideA">Side A</label>
                    <div class="photo-box">
                        <input type="file" class="filepond1" name="filepond1" data-max-file-size="6MB" />
                    </div>
                    <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label" for="SideB">Side B</label>
                    <div class="photo-box">
                        <input type="file" class="filepond2" name="filepond2" data-max-file-size="6MB" />
                    </div>
                    <textarea class="form-control" name="SideB" id="SideB" rows="5"></textarea>
                </div>

                <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"];?>"/>
                <input  type="hidden" name="CardNum" value="<?php echo $Next; ?>"/>

                <input type="submit" value="Add Card" class="btn btn-primary">
                <a href="AllCards.php?SetID=<?php echo $_GET["SetID"];?>" class="btn btn-danger">Cancel</a>

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

?>
    <script>

        FilePond.registerPlugin(
            FilePondPluginFileEncode,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview
        );

        const pond1 = FilePond.create( document.querySelector('.filepond1'), {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
            allowFileEncode: true,
            required: false,
            instantUpload: false

        });

        const pond2 = FilePond.create( document.querySelector('.filepond2'), {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
            allowFileEncode: true,
            required: false,
            instantUpload: false

        });

    </script>
<?php

$OUTPUT->footerEnd();
