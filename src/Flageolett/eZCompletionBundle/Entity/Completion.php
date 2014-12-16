<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 11.12.14
 */

namespace Flageolett\eZCompletionBundle\Entity;

class Completion
{
    public $lookupValue;
    public $returnValue;

    public function __construct($lookupValue, $returnValue)
    {
        $this->lookupValue = $lookupValue;
        $this->returnValue = $returnValue;
    }
}
