<?php

include_once('model\search_parameters.php');

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
 * Search query controller.
 */
class SearchController extends BaseController
{
    private SearchParameters $searchParameters;

    /**
     * Default constructor.
     */
    function __construct($arguments, $body)
    {
        $this->searchParameters = new SearchParameters($arguments, $body);
    }

    /**
     * Perform action here.
     */
    public function process_it(): array
    {
        if ($this->searchParameters->validate_action()) {
            if ($this->searchParameters->is_variable_list()) return $this->list_variables();
            if ($this->searchParameters->is_compute()) return $this->compute_marks();
            if ($this->searchParameters->is_rules()) return $this->list_rules();
        } else {
            return errorNoAction($this->searchParameters->get_action());
        }
    }

    /**
     * Action for the list variables.
     */
    private function list_variables(): array
    {
        $query = TextUtils::list_variable_query($this->searchParameters->get_rule_id());
        return search_variables($query);
    }

    /**
     * List all the rules filtered by the given parameters.
     */
    private function list_rules()
    {
        $query = TextUtils::list_rules_query($this->searchParameters->get_request_body());
        return search_rules($query);
    }

    /**
     * Action for the compute internal marks.
     */
    private function compute_marks(): array
    {
        if ($this->validate_variables()) {
            $stored_rule = $this->get_rule_of();
            $body_variables = $this->searchParameters->variables_from_body();
            $result = $this->evaluate_rule($stored_rule, $body_variables);
            if ($result == -1.0) return errorVariableMismatch($this->searchParameters->get_action());
            else return evaluationResult($result, $this->searchParameters->get_rule_id());
        } else return errorVariableMismatch($this->searchParameters->get_action());
    }

    /**
     * Perform evaluation of the rule with the given parameters.
     */
    private function evaluate_rule($rule, $parameters): float
    {
        $new_rule = $rule;
        $params = $parameters;
        /**
         * Make variable replacements and perform evaluation.
         */
        foreach ($params as $variable) {
            $value = $this->searchParameters->value_from_body_of($variable);
            $new_rule = str_replace($variable, $value, $new_rule);
        }
        $result = -1.0;
        try {
            eval('$result = ' . $new_rule . ";");
        } catch (Error $e) {
            $result = -1.0;
        }
        return $result;
    }

    /**
     * Action for the get equation of the rule id.
     */
    private function get_rule_of(): string
    {
        $query = TextUtils::rule_query_of($this->searchParameters->get_rule_id());
        $rules = array_map(function ($o) {
            return $o["rule"];
        }, search_variables($query));
        return $rules[0];
    }

    /**
     * Validate the variable names with the body parameters.
     */
    private function validate_variables(): bool
    {
        $body_variables = $this->searchParameters->variables_from_body();
        $db_variables = array_map(function ($o) {
            return $o["name"];
        }, $this->list_variables());
        return array_diff($body_variables, $db_variables) == [];
    }
}
