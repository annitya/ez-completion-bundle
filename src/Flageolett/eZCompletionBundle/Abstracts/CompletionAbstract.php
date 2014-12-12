<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 26.11.14
 */

namespace Flageolett\eZCompletionBundle\Abstracts;

abstract class CompletionAbstract
{
    protected $language;

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    abstract public function getCompletions();
}
