<?php

/**
 * All the libraries.
 */
include_once('base_controller.php');
include_once('lib\JsonMapper.php');
include_once('model\rule.php');
include_once('model\messages.php');
include_once('lib\DBExecutor.php');
include_once('model\actions.php');

class MarksController extends BaseController
{
    /**
     * Internal class variables.
     */
    /**
     * @client_data, used to represent the client data.
     */
    private $client_data;

    /**
     * @rule_data, is used to store the rule data, is an instance of the class Rule.
     */
    private Rule $rule_data;

    /**
     * Receives the Action for the given operation as well.
     */
    private $action;

    /**
     * @param $client_data , json body data received from the client.
     */
    function __construct($client_data, $action)
    {
        $this->client_data = $client_data;
        $this->rule_data = $this->parse_client_data();
        $this->action = $action;
    }

    /**
     * This is the dispatcher of the assigned action name.
     * @return string[] or the computed result.
     */
    public function process_it(): array
    {
        if ($this->action == Actions::CREATE) return $this->create();
        if ($this->action == Actions::UPDATE) return $this->update();
        return errorNoAction(action_name_of($this->action));
    }

    private function parse_client_data()
    {
        $mapper = new JsonMapper();
        $ruleObject = $mapper->map(json_decode($this->client_data), Rule::class);
        return $ruleObject;
    }

    /**
     * Create new entries for the given rule.
     */
    private function create(): array
    {
        list($queries, $status, $rule_id) = $this->rule_data->create_rule();
        switch ($status) {
            case true:
                return execute_multiple_queries($queries, $rule_id);
            case false:
                return queryGenerationError($this->action);
        }
    }

    /**
     * Update the already entered rule identified by the given identifier.
     */
    private function update(): array
    {
        list($queries, $status, $rule_id) = $this->rule_data->update_rule();
        switch ($status) {
            case true:
                return execute_multiple_queries($queries, $rule_id);
            case false:
                return queryGenerationError($this->action);
        }
    }
}
