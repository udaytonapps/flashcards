<?php

$REGISTER_LTI2 = array(
    "name" => "Flashcards", // Name of the tool
    "FontAwesome" => "fa-clone", // Icon for the tool
    "short_name" => "Flashcards",
    "description" => "Create flashcard sets that students can use to study. Text, images, audio, or video can be added to cards.", // Tool description
    "messages" => array("launch"),
    "privacy_level" => "public",  // anonymous, name_only, public
    "license" => "Apache",
    "languages" => array(
        "English",
    ),
    "source_url" => "https://github.com/udaytonapps/flashcards",
    // For now Tsugi tools delegate this to /lti/store
    "placements" => array(
        /*
        "course_navigation", "homework_submission",
        "course_home_submission", "editor_button",
        "link_selection", "migration_selection", "resource_selection",
        "tool_configuration", "user_navigation"
        */
    ),
    "video" => "https://udayton.warpwire.com/w/FfsEAA/",
    "screen_shots" => array(
        "images/Using-Flash-Card.png",
        "images/New-Card-Set.png",
        "images/Card-Sets.png",
        "images/Setting-up-Card.png",
        "images/Card.png",
        "images/Edit-Flashcard.png",
        "images/Flashcard-Isidore.png",
    )
);
