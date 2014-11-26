<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 26.11.14
 */

namespace Flageolett\ezcompletionbundle\Service;

use Flageolett\ezcompletionbundle\Interfaces\CompletionInterface;

class CompletionService implements CompletionInterface
{
    /** @var CompletionInterface[] */
    protected $completionServices;

    public function addCompletionService($completionService)
    {
        $this->completionServices[] = $completionService;
    }

    public function getCompletions()
    {
        $completions = array();
        foreach ($this->completionServices as $completionService) {
            $completions += $completionService->getCompletions();
        }

        return $completions;
    }
}
