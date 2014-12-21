<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 21.12.14
 */

namespace Flageolett\eZCompletionBundle\Traits;

trait LanguageAware
{
    protected $language;

    public function setLanguage($langauge)
    {
        $this->language = $langauge;
    }
}
