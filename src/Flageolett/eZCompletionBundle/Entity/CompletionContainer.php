<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 11.12.14
 */

namespace Flageolett\eZCompletionBundle\Entity;

class CompletionContainer
{
    public $method;
    public $parameterIndex;
    public $completions;

    public function __construct($method, $parameterIndex, $completions)
    {
        $this->method = $method;
        $this->parameterIndex = $parameterIndex;
        $this->completions = $completions;
    }
}
