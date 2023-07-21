<?php

/**
 * No method allowed.
 * @return string[]
 */
function errorNoMethod(): array
{
    $json = array("status" => "fail", "message" => "Method not allowed !");
    return $json;
}

/**
 * No action defined.
 * @return string[]
 */
function errorNoAction($action): array
{
    $json = array("status" => "fail", "action" => $action, "message" => "No action defined with name $action !");
    return $json;
}

/**
 * Duplicate entry error formatter.
 * @param $message
 * @return array
 */
function duplicateEntryError($action, $message)
{
    $json = array("status" => "fail", "action" => $action, "message" => $message);
    return $json;
}

/**
 * Database error.
 * @param $action
 * @param $message
 * @return array
 */
function dbError($action, $message)
{
    $json = array("status" => "fail", "action" => $action, "message" => $message);
    return $json;
}

/**
 * Message for invalid rule.
 * @return string[]
 */
function invalidRule(): array
{
    $json = array("status" => "fail", "message" => "Invalid rule expression !");
    return $json;
}

/**
 * Generate message for the successful execution of create rules.
 */
function rulesCreated(string $id, int $no): array
{
    $json = array(
        "status" => "success",
        "rule_id" => $id,
        "number_of_changes" => $no
    );
    return $json;
}

/**
 * Error while creating rule.
 */
function errorOnRuleCreation(string $id): array
{
    $json = array(
        "status" => "fail",
        "rule_id" => $id,
        "message" => "Error occurred while creating the rule in the DB."
    );
    return $json;
}

/**
 * No method allowed.
 * @return string[]
 */
function invalidURLFormat(): array
{
    $json = array("status" => "fail", "message" => "URL does not contain valid path of 'host/expression/\<action\>'");
    return $json;
}

function errorVariableMismatch($action): array
{
    $json = array(
        "status" => "fail",
        "action" => $action,
        "message" => "Given one or more variables are mismatched with the action '$action' !"
    );
    return $json;
}

/**
 * Build evaluation response.
 */
function evaluationResult($result, $rule_id): array
{
    $json = array(
        "status" => "success",
        "result" => $result,
        "rule_id" => $rule_id,
        "message" => "Rule evaluation completed successfully."
    );
    return $json;
}

/**
 * Build query generation error.
 */
function queryGenerationError($action): array
{
    $json = array(
        "status" => "fail",
        "action" => $action,
        "message" => "Unable to generate the subsequent queries for the action '$action' !"
    );
    return $json;
}
