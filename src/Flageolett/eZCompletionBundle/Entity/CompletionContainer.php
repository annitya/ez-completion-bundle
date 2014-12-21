<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 11.12.14
 */

namespace Flageolett\eZCompletionBundle\Entity;

class CompletionContainer
{
    public $matcher;
    public $completions;

    public function __construct($method, $parameterIndex, $completions)
    {
        $this->matcher = compact('method', 'parameterIndex');
        $this->completions = $completions;
    }
}
