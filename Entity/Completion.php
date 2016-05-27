<?php

namespace Flageolett\eZCompletionBundle\Entity;

class Completion
{
    public $lookupValue;
    public $returnValue;
    public $keepQuotes;

    public function __construct($lookupValue, $returnValue)
    {
        $this->lookupValue = $lookupValue;
        $this->returnValue = $returnValue;
        $this->keepQuotes = !is_int($returnValue);
    }
}
