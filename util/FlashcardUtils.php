<?php

class FlashcardUtils {

    public static function compareCardNum($a, $b) {

        if ($a["CardNum"] == $b["CardNum"]) {
            return 0;
        }
        return ($a["CardNum"] < $b["CardNum"]) ? -1 : 1;
    }

    public static function compareCardNum2($a, $b) {

        if ($a["CardNum2"] == $b["CardNum2"]) {
            return 0;
        }
        return ($a["CardNum2"] < $b["CardNum2"]) ? -1 : 1;
    }

    // Comparator for student last name used for sorting roster
    public static function compareStudentsLastName($a, $b) {
        return strcmp($a->family_name, $b->family_name);
    }

}