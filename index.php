<?php

include_once('model\messages.php');
include_once('lib\text_utils.php');
include_once('model\search_parameters.php');
include_once('controller\marks_controller.php');
include_once('controller\search_controller.php');

/**
 * We only allow the POST method for the API input.
 */
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /**
     *  Receive body JSON and process it in the controller.
     */
    $client_data = file_get_contents('php://input');
    $marksController = new MarksController($client_data, Actions::CREATE);
    echo TextUtils::format_response($marksController->process_it());
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $client_data = TextUtils::parse_url_params($_SERVER['REQUEST_URI']);
    $body_data = file_get_contents('php://input');
    if (empty($body_data)) {
        $body_data = "{}";
    }
    $searchController = new SearchController($client_data, $body_data);
    echo TextUtils::format_response($searchController->process_it());
} else if ($_SERVER['REQUEST_METHOD'] == "PATCH") {
    $client_data = file_get_contents('php://input');
    $marksController = new MarksController($client_data, Actions::UPDATE);
    echo TextUtils::format_response($marksController->process_it());
} else {
    echo TextUtils::format_response(errorNoMethod());
}
