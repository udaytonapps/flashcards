<?php
require_once "../../config.php";
require_once "../util/PHPExcel.php";
require_once "../dao/FlashcardsDAO.php";
require_once "../util/FlashcardUtils.php";

use \Tsugi\Core\LTIX;
use \Flashcards\DAO\FlashcardsDAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$flashcardsDAO = new FlashcardsDAO($PDOX, $p);

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $set = $flashcardsDAO->getFlashcardSetById($setId);

    $cardsInSet = $flashcardsDAO->getCardsInSet($setId);

    usort($cardsInSet, array('FlashcardUtils', 'compareCardNum'));

    $totalCards = count($cardsInSet);

    $exportFile = new PHPExcel();

    $exportFile->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Student Name');

    $hasRosters = LTIX::populateRoster(false);

    if ($hasRosters) {

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, array('FlashcardUtils', 'compareStudentsLastName'));

        $columnIterator = $exportFile->getActiveSheet()->getColumnIterator();
        $columnIterator->next();

        $x = 1;
        foreach($cardsInSet as $card) {

            $exportFile->getActiveSheet()->setCellValue($columnIterator->current()->getColumnIndex().'1', 'Card '.$x);

            $rowCounter = 2;

            foreach($rosterData as $student) {

                // Only want students
                if ($student["role"] == 'Learner') {
                    $exportFile->getActiveSheet()
                        ->setCellValue('A'.$rowCounter, $student["person_name_family"].', '.$student["person_name_given"]);

                    $userId = $flashcardsDAO->getTsugiUserId($student["user_id"]);

                    if (!$userId) {
                        $completed = false;
                    } else {
                        $completed = $flashcardsDAO->activityExists($card["CardID"], $userId);
                    }

                    if($completed) {
                        $exportFile->getActiveSheet()->setCellValue($columnIterator->current()->getColumnIndex().$rowCounter, 'X');
                    }

                    $rowCounter++;
                }
            }
            $columnIterator->next();

            ++$x;
        }

        $exportFile->getActiveSheet()->setTitle('Flashcards Activity');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="flashcards_activity.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($exportFile, 'Excel5');
        $objWriter->save('php://output');
    }
}

exit;