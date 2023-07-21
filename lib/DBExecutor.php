<?php

include_once('config\database.php');

/**
 * This class is a part of the package  and the package
 * is a part of the project expression.
 *
 * Integrated ICT Pvt. Ltd. Jwagal, Lalitpur, Nepal.
 * https://www.integratedict.com.np
 * https://www.semantro.com
 *
 * Created by Santa on 2023-06-17.
 * Email: sbasnet81[at]gmail[dot]com
 * Github: https://github.com/santabasnet
 */

/**
 * Global variable for the db connection and utility.
 */
$db = new Database();

/**
 * Perform the execution of multiple queries.
 * @param $queries , a list of queries.
 * @param $id , the generated id for the given new rule.
 * @return array, either successful or error message.
 */
function execute_multiple_queries(array $queries, string $rule_id): array
{
    try {
        global $db;
        return $db->insert($queries, $rule_id);
    } catch (Exception $e) {
        return dbError("create", $e->getMessage());
    }
}

/**
 * Function that list all the variables associated with rule_id.
 */
function search_variables(string $query): array
{
    global $db;
    return $db->select($query);
}

/**
 * Function that executes the search variables.
 */
function search_rules(string $query): array
{
    return search_variables($query);
}
