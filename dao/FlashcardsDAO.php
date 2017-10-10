<?php
namespace Flashcards\DAO;

class FlashcardsDAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }

    function getShortCutSetIdForLink($link_id) {
        $query = "SELECT SetId FROM {$this->p}flashcards_link WHERE link_id = '".$link_id."';";
        return $this->PDOX->rowDie($query);
    }

}