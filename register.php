<?php

$REGISTER_LTI2 = array(
    "name" => "Flashcards", // Name of the tool
    "FontAwesome" => "fa-clone", // Icon for the tool
    "short_name" => "Flashcards",
    "description" => "Create flashcard sets that include text, images, MP3s, or videos that your students can use to study important material in your course sites. They are fun and work great on a browser or mobile device.", // Tool description
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
    "screen_shots" => array(
    )
);
