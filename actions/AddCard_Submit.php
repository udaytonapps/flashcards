<?php
require_once "../../config.php";
require_once('../dao/FlashcardsDAO.php');

use Tsugi\Blob\BlobUtil;
use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

$blob_id = null;
$MediaA = null;
$TypeA = "Media";
if($_POST['filepond1'])
{
    $file_arr = $_POST['filepond1'];
    $fdes = json_decode($file_arr, true);

    $filename = isset($fdes['name']) ? basename($fdes['name']) : false;

    $tmp_name = '/tmp/' . $filename;

    $eData = $fdes['data'];
    $dData = base64_decode($eData);

    file_put_contents($tmp_name, $dData);

    $fdes['tmp_name'] = $tmp_name;

    // Sanity-check the file
    $safety = BlobUtil::validateUpload($fdes);
    if ( $safety !== true ) {
        $_SESSION['error'] = "Error: ".$safety;
        error_log("Upload Error: ".$safety);
        header( 'Location: '.addSession('index.php') ) ;
        return;
    }

    $blob_id = BlobUtil::uploadToBlob($fdes);
    if ( $blob_id === false ) {
        $_SESSION['error'] = 'Problem storing file in server: '.$filename;
        header( 'Location: '.addSession('index.php') ) ;
        return;
    }

    $MediaA = $blob_id;
}

$MediaB = null;
$TypeB = "Media";
if($_POST['filepond2'])
{
    $file_arr = $_POST['filepond2'];
    $fdes = json_decode($file_arr, true);

    $filename = isset($fdes['name']) ? basename($fdes['name']) : false;

    $tmp_name = '/tmp/' . $filename;

    $eData = $fdes['data'];
    $dData = base64_decode($eData);

    file_put_contents($tmp_name, $dData);

    $fdes['tmp_name'] = $tmp_name;

    // Sanity-check the file
    $safety = BlobUtil::validateUpload($fdes);
    if ( $safety !== true ) {
        $_SESSION['error'] = "Error: ".$safety;
        error_log("Upload Error: ".$safety);
        header( 'Location: '.addSession('index.php') ) ;
        return;
    }

    $blob_id = BlobUtil::uploadToBlob($fdes);
    if ( $blob_id === false ) {
        $_SESSION['error'] = 'Problem storing file in server: '.$filename;
        header( 'Location: '.addSession('index.php') ) ;
        return;
    }

    $MediaB = $blob_id;
}

$SetID=$_POST["SetID"];

$SideA = isset($_POST['SideA']) ? $_POST['SideA'] : null;
$SideB = isset($_POST['SideB']) ? $_POST['SideB'] : null;

$CardNum = $_POST["CardNum"];

if ( $USER->instructor ) {

    $flashcardsDAO->createCard($SetID, $CardNum, $SideA, $MediaA, $SideB, $MediaB, $TypeA, $TypeB);

    header( 'Location: '.addSession('../AllCards.php?SetID='.$SetID) ) ;
} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}
