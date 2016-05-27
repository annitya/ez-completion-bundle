<?php

namespace Flageolett\eZCompletionBundle\Entity;

class DependentCompletionContainer extends CompletionContainer
{
    public function setDependence($dependence)
    {
        $this->matcher['dependence'] = $dependence;
    }
}
