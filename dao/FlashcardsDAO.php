<?php
namespace Flashcards\DAO;

class FlashcardsDAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }

    function getShortcutSetIdForLink($link_id) {
        $query = "SELECT * FROM {$this->p}flashcards_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $link_id);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getFlashcardSetById($setId) {
        $query = "SELECT * FROM {$this->p}flashcards_set WHERE SetID = '".$setId."';";
        return $this->PDOX->rowDie($query);
    }

    function getAllSetsForSiteSorted($context_id) {
        $query = "SELECT * FROM {$this->p}flashcards_set WHERE context_id='".$context_id."' ORDER BY CardSetName;";
        return $this->PDOX->allRowsDie($query);
    }

    function getAllVisibleSetsForSiteSorted($context_id) {
        $query = "SELECT * FROM {$this->p}flashcards_set where context_id = :contextId AND Active=1 AND Visible=1 ORDER BY CardSetName;";
        $arr = array(':contextId' => $context_id);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getAllSitesForUserWithACardSet($userId, $context_id) {
        $query = "SELECT DISTINCT context_id FROM {$this->p}flashcards_set where UserID='".$userId."' AND context_id !='".$context_id."' AND Visible=1";
        return $this->PDOX->allRowsDie($query);
    }

    function getCardsInSet($setId) {
        $query = "SELECT * FROM {$this->p}flashcards where SetID='".$setId."';";
        return $this->PDOX->allRowsDie($query);
    }

    function createCardSet($userId, $context_id, $cardSetName) {
        $query = "INSERT INTO {$this->p}flashcards_set (UserID, context_id, CardSetName) VALUES (:userId, :contextId, :cardSetName);";
        $arr = array(':userId' => $userId, ':contextId' => $context_id, ':cardSetName' => $cardSetName);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateCardSet($setId, $cardSetName, $active) {
        $query = "UPDATE {$this->p}flashcards_set SET CardSetName = :cardSetName, Active = :active WHERE SetID = :setId;";
        $arr = array(':cardSetName' => $cardSetName, ':active' => $active, ':setId' => $setId);
        $this->PDOX->queryDie($query, $arr);
    }

    function togglePublishCardSet($setId, $toggle) {
        $query = "UPDATE {$this->p}flashcards_set SET Active = :toggle WHERE SetID = :setId;";
        $arr = array(':toggle' => $toggle, ':setId' => $setId);
        $this->PDOX->queryDie($query, $arr);
    }

    function createCard($setId, $cardNum, $sideA, $sideB, $typeA, $typeB) {
        $query = "INSERT INTO {$this->p}flashcards (SetID, CardNum, SideA, SideB, TypeA, TypeB) VALUES (:setId, :cardNum, :sideA, :sideB, :typeA, :typeB);";
        $arr = array(':setId' => $setId, ':cardNum' => $cardNum, ':sideA' => $sideA, ':sideB' => $sideB, ':typeA' => $typeA, ':typeB' => $typeB);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function getCardById($cardId) {
        $query = "SELECT * FROM {$this->p}flashcards WHERE CardID = :cardId;";
        $arr = array(':cardId' => $cardId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getCardBySetAndNumber($setId, $cardNum) {
        $query = "SELECT * FROM {$this->p}flashcards WHERE CardNum = :cardNum AND SetID = :setId;";
        $arr = array(':cardNum' => $cardNum, ':setId' => $setId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getCardBySetAndNumber2($setId, $cardNum2) {
        $query = "SELECT * FROM {$this->p}flashcards WHERE CardNum2 = :cardNum2 AND SetID = :setId;";
        $arr = array(':cardNum2' => $cardNum2, ':setId' => $setId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function updateCard($cardId, $sideA, $sideB, $typeA, $typeB) {
        $query = "UPDATE {$this->p}flashcards set SideA = :sideA, SideB = :sideB, TypeA = :typeA, TypeB = :typeB where CardID = :cardId;";
        $arr = array(':sideA' => $sideA, ':sideB' => $sideB, ':typeA' => $typeA, ':typeB' => $typeB, ':cardId' => $cardId);
        $this->PDOX->queryDie($query, $arr);
    }

    function updateCardNumber($cardId, $cardNum) {
        $query = "UPDATE {$this->p}flashcards set CardNum = :cardNum where CardID = :cardId;";
        $arr = array(':cardNum' =>$cardNum, ':cardId' => $cardId);
        $this->PDOX->queryDie($query, $arr);
    }

    function updateCardNumber2($cardNum2, $cardNum, $setId) {
        $query = "UPDATE {$this->p}flashcards SET CardNum2 = :cardNum2 WHERE SetID = :setId AND CardNum = :cardNum;";
        $arr = array(':cardNum2' => $cardNum2, ':setId' => $setId, ':cardNum' => $cardNum);
        $this->PDOX->queryDie($query, $arr);
    }

    function deleteCard($cardId) {
        $query = "DELETE FROM {$this->p}flashcards WHERE CardID = :cardId;";
        $arr = array(':cardId' => $cardId);
        $this->PDOX->queryDie($query, $arr);
    }

    function deleteAllCardsInSet($setId) {
        $query = "DELETE FROM {$this->p}flashcards WHERE SetID = :setId;";
        $arr = array(':setId' => $setId);
        $this->PDOX->queryDie($query, $arr);
    }

    function deleteCardSet($setId) {
        $query = "DELETE FROM {$this->p}flashcards_set WHERE SetID = :setId ;";
        $arr = array(':setId' => $setId);
        $this->PDOX->queryDie($query, $arr);
    }

    function getNumberOfSeenCards($userId, $setId) {
        $query = "SELECT count(distinct(CardID)) as Count FROM {$this->p}flashcards_activity WHERE UserID = :userId AND SetID = :setId;";
        $arr = array(':userId' => $userId, ':setId' => $setId);
        $count = $this->PDOX->rowDie($query, $arr);
        return $count["Count"];
    }

    function updateActivityForUserCard($setId, $cardId, $userId) {
        if(!$this->activityExists($cardId, $userId)) {
            $query = "INSERT INTO {$this->p}flashcards_activity (SetID, CardID, UserID) VALUES ( :setId, :cardId, :userId);";
            $values = array(':setId' => $setId, ':cardId' => $cardId, ':userId' => $userId);
            $this->PDOX->queryDie($query, $values);
        }
    }

    function getCourseNameForId($context_id) {
        $query = "SELECT title FROM {$this->p}lti_context WHERE context_id = :contextId;";
        $arr = array(':contextId' => $context_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["title"];
    }

    function activityExists($cardId, $userId) {
        $query = "SELECT * FROM {$this->p}flashcards_activity WHERE CardID = :cardId AND UserID = :userId;";
        $arr = array(':cardId' => $cardId, ':userId' => $userId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }

    function toggleReview($userId, $setId, $cardId) {
        if(!$this->cardKnown($userId, $setId, $cardId)) {
            $query = "INSERT INTO {$this->p}flashcards_review (UserID, SetID, CardID) VALUES ( :userId, :setId, :cardId);";
        } else {
            $query = "DELETE FROM {$this->p}flashcards_review WHERE UserID = :userId AND SetID = :setId AND CardID = :cardId;";
        }
        $arr = array(':userId' => $userId, ':setId' => $setId, ':cardId' => $cardId);
        $this->PDOX->rowDie($query, $arr);
    }

    function cardKnown($userId, $setId, $cardId) {
        $query = "SELECT * FROM {$this->p}flashcards_review WHERE UserID = :userId AND SetID = :setId AND CardID = :cardId;";
        $arr = array(':userId' => $userId, ':setId' => $setId, ':cardId' => $cardId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }

    function getKnownCards($userId, $setId) {
        $query = "SELECT * FROM {$this->p}flashcards_review WHERE UserID = :userId AND SetID = :setId;";
        $arr = array(':userId' => $userId, ':setId' => $setId);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function deleteAllCardReviews($userId, $setId) {
        $query = "DELETE FROM {$this->p}flashcards_review WHERE UserID = :userId AND SetID = :setId;";
        $arr = array(':userId' => $userId, ':setId' => $setId);
        $this->PDOX->queryDie($query, $arr);
    }

    function getLinkedSet($linkId) {
        $query = "SELECT * FROM {$this->p}flashcards_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function saveOrUpdateLink($setId, $linkId) {
         if ($this->linkIsSet($linkId)) {
             $query = "UPDATE {$this->p}flashcards_link SET SetID = :setId WHERE link_id = :linkId;";
         } else {
             $query = "INSERT INTO {$this->p}flashcards_link (SetID, link_id) VALUES (:setId, :linkId);";
         }
         $arr = array(':setId' => $setId, ':linkId' => $linkId);
         $this->PDOX->queryDie($query, $arr);
    }

    function deleteLink($linkId) {
        $query = "DELETE FROM {$this->p}flashcards_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        $this->PDOX->queryDie($query, $arr);
    }

    private function linkIsSet($linkId) {
        $query = "SELECT * FROM {$this->p}flashcards_link WHERE link_id = :linkId;";
        $arr = array(':linkId' => $linkId);
        $theLink = $this->PDOX->rowDie($query, $arr);
        return $theLink !== false;
    }
}