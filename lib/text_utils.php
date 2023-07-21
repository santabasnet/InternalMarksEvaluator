<?php

include_once('model\constants.php');
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
 * An implementation of text utility functionalities.
 */
class TextUtils
{
    /**
     * Extract URI parameters.
     * @param $uri
     * @return array
     */
    public static function parse_url_params(string $uri): array
    {
        $params = array();
        $url_components = parse_url($uri);
        if (array_key_exists('query', $url_components)) {
            parse_str($url_components['query'], $params);
            return $params;
        } else return array();
    }

    /**
     * Format the response with JSON pretty print.
     * @param $response
     * @return bool|string
     */
    public static function format_response($response): bool|string
    {
        return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate variable query associated with rule_id.
     */
    public static function list_variable_query($rule_id): string
    {
        $variable_query = sprintf(LIST_VARIABLES, $rule_id);
        return $variable_query;
    }

    public static function list_rules_query($filter): string
    {
        $params = 1;
        if (!empty($filter)) {
            $params = [];
            foreach ($filter as $key => $value) {
                array_push($params, "`{$key}` = '{$value}'");
            }
            $params = join(" AND ", $params);
        }
        $rules_query = sprintf(LIST_RULES, $params);
        return $rules_query;
    }

    public static function rule_query_of($rule_id): string
    {
        $rule_query = sprintf(RULE_OF, $rule_id);
        return $rule_query;
    }
}
