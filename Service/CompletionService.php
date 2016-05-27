<?php

namespace Flageolett\eZCompletionBundle\Service;

use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;
use Flageolett\eZCompletionBundle\Traits\LanguageAware;

class CompletionService
{
    use LanguageAware;

    /** @var CompletionAbstract[] */
    protected $completionServices;

    public function addCompletionService(CompletionAbstract $completionService, $config)
    {
        $completionService->setConfig($config);
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
}
