<?php
require_once "../config.php";
require_once "dao/FlashcardsDAO.php";

use Tsugi\Blob\BlobUtil;
use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$_SESSION['serve1'] = null;
$_SESSION['serve2'] = null;
$_SESSION['typeA'] = null;
$_SESSION['typeB'] = null;

$OUTPUT->header();

include("tool-header.html");

?>
<style>
    .col-sm-offset-1 {
        margin-bottom: 20px;
    }
</style>
<?php

$OUTPUT->bodyStart();

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $card = $flashcardsDAO->getCardById($_GET["CardID"]);
    if($card['TypeA'] == 'Image') {
        $_SESSION['serve1'] = $card['SideA'];
        $_SESSION['typeA'] = 'Image';
    } else if($card['TypeA'] == 'Media') {
        if(isset($card['MediaA'])) {
            $_SESSION['serve1'] = BlobUtil::getAccessUrlForBlob($card['MediaA']);
            $_SESSION['typeA'] = 'Media';
        }
    }
    if($card['TypeB'] == 'Image') {
        $_SESSION['serve1'] = $card['SideB'];
        $_SESSION['typeB'] = 'Image';
    } else if($card['TypeB'] == 'Media') {
        if(isset($card['MediaB'])) {
            $_SESSION['serve2'] = BlobUtil::getAccessUrlForBlob($card['MediaB']);
            $_SESSION['typeB'] = 'Media';
        }
    }

    include("menu.php");

    echo('
        <ul class="breadcrumb">
            <li><a href="index.php">All Card Sets</a></li>
            <li><a href="AllCards.php?SetID=' .$setId.'">'.$set["CardSetName"].'</a></li>
            <li>Edit Card</li>
        </ul>
    ');

    if($card['TypeA'] == 'Video') {
        ?>
        <form method="post" id="editCard" action="actions/EditCard_Submit.php">

            <div class="row">
                <div class="col-sm-offset-1 col-sm-8">
                    <h3>Editing Card <?php echo($card["CardNum"]); ?></h3>
                </div>
                <div class="col-sm-offset-1 col-sm-8">
                    <p>WARNING: Videos are no longer supported, and cards with videos may no longer be edited.</p>
                    <div class="form-group">
                        <label class="control-label" for="SideA">Side A</label>
                        <?php
                        if ($card['TypeA'] == 'Text') {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      required><?php echo $card['SideA'] ?></textarea>
                            <?php
                        } else if ($card['TypeA'] == 'Media' && $card['SideA'] != null) {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      required><?php echo $card['SideA'] ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      required disabled></textarea>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="form-group" disabled>
                        <label class="control-label" for="SideB">Side B</label>
                        <?php
                        if ($card['TypeB'] == 'Text') {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      required><?php echo $card['SideB'] ?></textarea>
                            <?php
                        } else if ($card['TypeB'] == 'Media' && $card['SideB'] != null) {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      required><?php echo $card['SideB'] ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      required disabled></textarea>
                            <?php
                        }
                        ?>
                    </div>

                    <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"]; ?>"/>
                    <input type="hidden" name="CardID" value="<?php echo $_GET["CardID"]; ?>"/>

                    <input type="submit" value="Save Card" class="btn btn-primary" disabled>
                    <a href="AllCards.php?SetID=<?php echo $_GET["SetID"]; ?>" class="btn btn-danger">Cancel</a>

                </div>
            </div>
        </form>

        <?php
    } else {
        ?>
        <form method="post" id="editCard" action="actions/EditCard_Submit.php">

            <div class="row">
                <div class="col-sm-offset-1 col-sm-8">
                    <h3>Editing Card <?php echo($card["CardNum"]); ?></h3>
                </div>

                <div class="col-sm-offset-1 col-sm-8">
                    <div class="form-group">
                        <label class="control-label" for="SideA">Side A</label>
                        <div class="photo-box">
                            <input type="file" class="filepond1" name="filepond1" data-max-file-size="6MB"/>
                        </div>
                        <?php
                        if ($card['TypeA'] == 'Text') {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      ><?php echo $card['SideA'] ?></textarea>
                            <?php
                        } else if ($card['TypeA'] == 'Media' && $card['SideA'] != null) {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      ><?php echo $card['SideA'] ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea class="form-control" name="SideA" id="SideA" rows="5" autofocus
                                      ></textarea>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="SideB">Side B</label>
                        <div class="photo-box">
                            <input type="file" class="filepond2" name="filepond2" data-max-file-size="6MB"/>
                        </div>
                        <?php
                        if ($card['TypeB'] == 'Text') {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      ><?php echo $card['SideB'] ?></textarea>
                            <?php
                        } else if ($card['TypeB'] == 'Media' && $card['SideB'] != null) {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      ><?php echo $card['SideB'] ?></textarea>
                            <?php
                        } else {
                            ?>
                            <textarea class="form-control" name="SideB" id="SideB" rows="5" autofocus
                                      ></textarea>
                            <?php
                        }
                        ?>
                    </div>

                    <input type="hidden" name="SetID" value="<?php echo $_GET["SetID"]; ?>"/>
                    <input type="hidden" name="CardID" value="<?php echo $_GET["CardID"]; ?>"/>

                    <input type="submit" value="Save Card" class="btn btn-primary">
                    <a href="AllCards.php?SetID=<?php echo $_GET["SetID"]; ?>" class="btn btn-danger">Cancel</a>

                </div>
            </div>
        </form>

        <?php
    }
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
            instantUpload: false,
            labelTapToUndo: 'tap to remove'
            // onaddfilestart: (file) => { isLoadingCheck1(); },
            // onprocessfile: (file) => { isLoadingCheck1(); }

        });

        let typeA = '<?php echo $_SESSION['typeA'] == null ? null : $_SESSION['typeA'] ?>';

        let serve1 = '';
        if(typeA === 'Image') {
            serve1 = '<?php echo $_SESSION['serve1'] == null ? null : $_SESSION['serve1'] ?>';
            if(serve1.length > 14) {
                let request = new XMLHttpRequest();
                request.open('GET', serve1, true);
                request.responseType = 'blob';
                request.onload = function() {
                    let reader = new FileReader();
                    reader.readAsDataURL(request.response);
                    reader.onload = function(e) {
                        pond1.setOptions({
                            files: [
                                {
                                    source: e.target.result
                                }
                            ]
                        })
                    }
                };
                request.send();
            }
        } else if(typeA === 'Media') {
            serve1 = '<?php echo $_SESSION['serve1'] == null ? null : addSession($_SESSION['serve1']) ?>';
            if(serve1.length > 14) {
                pond1.setOptions({
                    files: [
                        {
                            source: serve1
                        }
                    ]
                })
            }

        }
        const pond2 = FilePond.create( document.querySelector('.filepond2'), {
            acceptedFileTypes: ['image/*'],
            allowMultiple: false,
            allowFileEncode: true,
            required: false,
            instantUpload: false,
            labelTapToUndo: 'tap to remove'
            // onaddfilestart: (file) => { isLoadingCheck2(); },
            // onprocessfile: (file) => { isLoadingCheck2(); }

        });

        let typeB = '<?php echo $_SESSION['typeB'] == null ? null : $_SESSION['typeB'] ?>';

        let serve2 = '';
        if(typeB === 'Image') {
            serve2 = '<?php echo $_SESSION['serve2'] == null ? null : $_SESSION['serve2'] ?>';
            if(serve2.length > 14) {
                let request = new XMLHttpRequest();
                request.open('GET', serve2, true);
                request.responseType = 'blob';
                request.onload = function() {
                    let reader = new FileReader();
                    reader.readAsDataURL(request.response);
                    reader.onload = function(e) {
                        pond2.setOptions({
                            files: [
                                {
                                    source: e.target.result
                                }
                            ]
                        })
                    }
                };
                request.send();
            }
        } else if(typeB === 'Media') {
            serve2 = '<?php echo $_SESSION['serve2'] == null ? null : addSession($_SESSION['serve2']) ?>';
            if(serve2.length > 14) {
                pond2.setOptions({
                    files: [
                        {
                            source: serve2
                        }
                    ]
                })
            }
        }

        let isDisabled = false;
        function isLoadingCheck1() {
            let isLoading1 = pond1.getFiles().filter(x=>x.status !== 5).length !== 0;
            let form = $('#editCard [type="submit"');
            if(isLoading1 && !isDisabled) {
                form.attr("disabled", "disabled");
                isDisabled = true;
            } else if(isDisabled) {
                form.removeAttr("disabled");
                isDisabled = false;
            }
        }

        function isLoadingCheck2() {
            let isLoading2 = pond2.getFiles().filter(x=>x.status !== 5).length !== 0;
            let form = $('#editCard [type="submit"');
            if(isLoading2 && !isDisabled) {
                form.attr("disabled", "disabled");
                isDisabled = true;
            } else if(isDisabled) {
                form.removeAttr("disabled");
                isDisabled = false;
            }
        }

    </script>
<?php

$OUTPUT->footerEnd();
