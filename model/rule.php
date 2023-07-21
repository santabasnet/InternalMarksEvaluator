<?php

include_once('vendor\autoload.php');
include_once('model\constants.php');
include_once('model\mark_token.php');

use MathParser\Lexing\StdMathLexer;
use MathParser\Lexing\TokenType;
use MathParser\StdMathParser;
use MathParser\Lexing\Token;
use Ramsey\Uuid\Uuid;

class Rule
{
    public $rule_id;
    public $teacher_id;
    public $subject_id;
    public $year;
    public $semester;
    public $category;
    public $rule;
    public $description;
    public $department;
    public $section;


    /**
     * 1. Generate insert Rule for new entry.
     * @return array $tuple of queries and the status after generation.
     */
    public function create_rule(): array
    {
        if ($this->validate()) {
            list($queries, $rule_id) = $this->generate_create_queries();
            return array($queries, true, $rule_id);
        } else
            return array(invalidRule(), false, "-1");
    }

    /**
     * 2. Generate update rule for the existing entries of the query.
     */
    public function update_rule(): array
    {
        if ($this->validate()) {
            list($queries, $rule_id) = $this->generate_update_queries();
            return array($queries, true, $rule_id);
        } else
            return array(invalidRule(), false, "-1");
    }

    /**
     * Generate batch of insert queries for the rule.
     * @return array
     */
    private function generate_create_queries(): array
    {
        $lexer = new StdMathLexer();
        $tokens = $lexer->tokenize($this->rule);
        /**
         * In default, it utilizes single character as an identifier. We need
         * some preprocessing for multiple character identifier.
         * It collects in the form form of  MarkToken array.
         */
        $marks_tokens = $this->combine_units($tokens);
        $rule_id = $this->generate_uuid();
        $queries = array_merge(
            $this->generate_rule_query($rule_id),
            $this->generate_variable_queries($marks_tokens, $rule_id)
        );
        return array($queries, $rule_id);
    }

    /**
     * Generate batch of delete and update queries for the rule.
     * @return array
     */
    private function generate_update_queries(): array
    {
        $lexer = new StdMathLexer();
        $tokens = $lexer->tokenize($this->rule);
        /**
         * In default, it utilizes single character as an identifier. We need
         * some preprocessing for multiple character identifier.
         * It collects in the form form of  MarkToken array.
         */
        $marks_tokens = $this->combine_units($tokens);
        $queries = array_merge(
            $this->delete_variables_query($this->rule_id),
            $this->rule_update_query($this->rule_id),
            $this->generate_variable_queries($marks_tokens, $this->rule_id)
        );
        return array($queries, $this->rule_id);
    }


    public function validate(): bool
    {
        return $this->hasValidVariables() and $this->hasValidRule();
    }

    /**
     * Validate the rule/equation part.
     */
    private function hasValidRule(): bool
    {
        try {
            $parser = new StdMathParser();
            $parser->parse($this->rule);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate the variables part.
     */
    private function hasValidVariables()
    {
        return
            !empty($this->teacher_id) or
            !empty($this->subject_id) or
            !empty($this->year) or
            !empty($this->semester) or
            !empty($this->category) or
            !empty($this->rule);
    }

    private function combine_units($tokens): array
    {
        /**
         * 1. Perform cleaning operation.
         */
        $cleaned_tokens = array_filter(
            $tokens,
            function ($token) {
                return $token->getValue() != ' ';
            }
        );

        /**
         * 2. Combine tokens.
         */
        $combined_tokens = [];
        $new_token = true;
        foreach ($cleaned_tokens as $token) {
            if ($this->is_operator($token)) {
                $mark_token = new MarkToken($this->operator_name($token), OPERATOR, $token->getValue());
                array_push($combined_tokens, $mark_token);
                $new_token = true;
            } else {
                if ($this->is_constant($token)) {
                    $constant_token = new MarkToken($token->getValue(), CONSTANT, $token->getValue());
                    array_push($combined_tokens, $constant_token);
                    $new_token = true;
                } else if ($this->is_identifier($token)) {
                    if ($new_token) {
                        $mark_token = new MarkToken($token->getValue(), VARIABLE, $token->getValue());
                        array_push($combined_tokens, $mark_token);
                    } else {
                        $last_token = end($combined_tokens);
                        $new_mark_token = $last_token->combine_with($token);
                        $removed = array_pop($combined_tokens);
                        array_push($combined_tokens, $new_mark_token);
                    }
                    $new_token = false;
                } else {
                    $mark_token = new MarkToken($token->getValue(), UNKNOWN, $token->getValue());
                    array_push($combined_tokens, $mark_token);
                    $new_token = true;
                }
            }
        }

        return $combined_tokens;
    }

    private function is_operator(Token $token): bool
    {
        return $token->getValue() == PLUS or
            $token->getValue() == MINUS or
            $token->getValue() == MULTIPLY or
            $token->getValue() == DIVIDE or
            $token->getValue() == EXPONENT;
    }

    private function operator_name($token): string
    {
        switch ($token->getValue()) {
            case PLUS:
                return "PLUS";
            case MINUS:
                return "MINUS";
            case MULTIPLY:
                return "MULTIPLY";
            case DIVIDE:
                return "DIVIDE";
            case EXPONENT:
                return "EXPONENT";
            default:
                return "UNKNOWN";
        }
    }

    private function is_constant($token): bool
    {
        return $token->getType() == TokenType::RealNumber or $token->getType() == TokenType::Integer;
    }

    private function is_identifier($token): bool
    {
        return $token->getType() == TokenType::Identifier or $token->getType() == TokenType::Constant;
    }

    /**
     * Generates insert queries for the variables.
     * @param $tokens
     * @return array
     */
    private function generate_variable_queries($tokens, $rule_id): array
    {
        /**
         * 1. Perform filter operation for variables only.
         */
        $variable_tokens = array_filter(
            $tokens,
            function ($token) {
                return $token->type == VARIABLE;
            }
        );

        $variable_queries = [];
        foreach ($variable_tokens as $token) {
            $variable_query = sprintf(VARIABLE_QUERY, $rule_id, $token->value, "float", "");
            array_push($variable_queries, $variable_query);
        }
        return $variable_queries;
    }

    /**
     * Generates delete queries for the existing variables.
     */
    private function delete_variables_query($rule_id): array
    {
        return array(sprintf(VARIABLE_DELETE_QUERY, $rule_id));
    }

    /**
     * Generate update query for the new rule.
     */
    private function rule_update_query($rule_id): array
    {
        return array(sprintf(UPDATE_RULE_QUERY, $this->rule, $rule_id));
    }

    /**
     * Generates
     * @return rule_queries, an array of rule query, in default, only one element.
     */
    private function generate_rule_query($rule_id): array
    {
        $rule_queries = [];
        $rule_query = sprintf(
            RULE_QUERY,
            $rule_id,
            $this->teacher_id,
            $this->subject_id,
            $this->year,
            $this->semester,
            $this->category,
            $this->rule,
            $this->description,
            $this->department,
            $this->section
        );
        array_push($rule_queries, $rule_query);
        return $rule_queries;
    }

    /**
     * Generates UUID for rule_id.
     */
    private function generate_uuid(): string
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }
}
