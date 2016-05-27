<?php

namespace Flageolett\eZCompletionBundle\Traits;

trait LanguageAware
{
    protected $language;

    public function setLanguage($langauge)
    {
        $this->language = $langauge;
    }
}
