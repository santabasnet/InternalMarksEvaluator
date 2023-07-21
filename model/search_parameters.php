<?php
/**
 * This class is a part of the package  and the package
 * is a part of the project expression.
 *
 * Integrated ICT Pvt. Ltd. Jwagal, Lalitpur, Nepal.
 * https://www.integratedict.com.np
 * https://www.semantro.com
 *
 * Created by Santa on 2023-06-18.
 * Email: sbasnet81[at]gmail[dot]com
 * Github: https://github.com/santabasnet
 */

/**
 * All the pre-defined search actions.
 */
const ALL_SEARCH_ACTIONS = [
    "variables",
    "rules",
    "compute"
];

class SearchParameters
{
    private array $arguments;
    private array $request_body;

    /**
     * Default constructor.
     */
    function __construct($arguments, $request_body)
    {
        $this->arguments = $arguments;
        $this->request_body = json_decode($request_body, true);
    }

    /**
     * Validate search actions.
     */
    function validate_action(): bool
    {
        return in_array($this->get_action(), ALL_SEARCH_ACTIONS);
    }

    /**
     * Get action name.
     */
    public function get_action()
    {
        return $this->arguments["action"] ?? "";
    }

    /**
     * Get rule id.
     */
    public function get_rule_id()
    {
        return $this->arguments["rule_id"] ?? "";
    }

    function get_arguments(): array
    {
        return $this->arguments;
    }

    function get_request_body()
    {
        return $this->request_body;
    }

    function is_variable_list(): bool
    {
        return $this->get_action() == ALL_SEARCH_ACTIONS[0];
    }

    function is_compute(): bool
    {
        return $this->get_action() == ALL_SEARCH_ACTIONS[2];
    }

    function is_rules(): bool
    {
        return $this->get_action() == ALL_SEARCH_ACTIONS[1];
    }

    function variables_from_body(): array
    {
        return array_keys($this->request_body);
    }

    function value_from_body_of($variable_name)
    {
        return $this->request_body[$variable_name];
    }
}
