<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 21.12.14
 */

namespace Flageolett\eZCompletionBundle\Entity;

class DependentCompletionContainer extends CompletionContainer
{
    public function setDependence($dependence)
    {
        $this->matcher['dependence'] = $dependence;
    }
}
