<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 26.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;

class CompletionService
{
    /** @var CompletionAbstract[] */
    protected $completionServices;
    /** @var string */
    protected $language;

    public function addCompletionService($completionService)
    {
        $this->completionServices[] = $completionService;
    }

    public function getCompletions()
    {
        $completions = array();
        foreach ($this->completionServices as $completionService) {
            $completionService->setLanguage($this->language);
            $completions = array_merge($completions, $completionService->getCompletions());
        }

        return $completions;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }
}
