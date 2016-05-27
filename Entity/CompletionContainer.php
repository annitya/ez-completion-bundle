<?php

namespace Flageolett\eZCompletionBundle\Entity;

class CompletionContainer
{
    public $matcher;
    public $completions;

    public function __construct($fqn, $method, $parameterIndex, $completions)
    {
        $this->matcher = compact('fqn', 'method', 'parameterIndex');
        $this->completions = $completions;
    }
}
