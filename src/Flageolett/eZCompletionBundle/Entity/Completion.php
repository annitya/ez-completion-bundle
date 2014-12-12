<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 11.12.14
 */

namespace Flageolett\eZCompletionBundle\Entity;

class Completion
{
    public $lookupValue;
    public $returnValue = false;

    public function __construct($lookupValue, $returnValue = false)
    {
        $this->lookupValue = $lookupValue;
        $this->returnValue = $returnValue ?: $lookupValue;
    }
}
