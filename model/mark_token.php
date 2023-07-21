<?php

use MathParser\Lexing\Token;

class MarkToken
{
    public $name;
    public $type;
    public $value;

    function __construct($name, $type, $value)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    function combine_with(Token $cToken): MarkToken
    {
        $name = $this->name . $cToken->getValue();
        $value = $this->value . $cToken->getValue();
        return new MarkToken($name, $this->type, $value);
    }

    public function __toString(): string
    {
        return "Token: [$this->name, $this->type, $this->value]";
    }

}

?>
