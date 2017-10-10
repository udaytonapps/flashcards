<?php
require_once "../../config.php";
require_once "../util/PHPExcel.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

// Comparator for student last name used for sorting roster
function compareStudentsLastName($a, $b) {
    return strcmp($a["person_name_family"], $b["person_name_family"]);
}

if ( $USER->instructor ) {

    $setId = $_GET["SetID"];

    $set = $PDOX->rowDie("select * from {$p}flashcards_set where SetID=".$setId.";");
    $cardsInSet = $PDOX->allRowsDie("SELECT * FROM {$p}flashcards where SetID=".$setId.";");

    $totalCards = count($cardsInSet);

    $exportFile = new PHPExcel();

    $exportFile->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Student Name');

    $hasRosters = LTIX::populateRoster(false);

    if ($hasRosters) {

        $rosterData = $GLOBALS['ROSTER']->data;

        usort($rosterData, "compareStudentsLastName");

        $columnIterator = $exportFile->getActiveSheet()->getColumnIterator();
        $columnIterator->next();

        for($x = 1; $x <= $totalCards; $x++) {

            $exportFile->getActiveSheet()->setCellValue($columnIterator->current()->getColumnIndex().'1', 'Card '.$x);

            $rowCounter = 2;

            foreach($rosterData as $student) {

                // Only want students
                if ($student["role"] == 'Learner') {
                    $exportFile->getActiveSheet()
                        ->setCellValue('A'.$rowCounter, $student["person_name_family"].', '.$student["person_name_given"]);

                    $completed = $PDOX->rowDie("SELECT count(*) as Count FROM {$p}flashcards_activity WHERE FullName = '".$student["person_name_full"]."' AND SetID = '".$setId."' AND CardNum = '".$x."';");

                    if($completed["Count"] == 1) {
                        $exportFile->getActiveSheet()->setCellValue($columnIterator->current()->getColumnIndex().$rowCounter, 'X');
                    }

                    $rowCounter++;
                }
            }
            $columnIterator->next();
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
        exit;
    }
}