<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\ezcompletionbundle\Service;

use eZ\Publish\API\Repository\ObjectStateService;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup;
use Flageolett\ezcompletionbundle\Interfaces\CompletionInterface;

class ObjectStateCompletion implements CompletionInterface
{
    /** @var ObjectStateService */
    protected $objectStateService;

    public function __construct(ObjectStateService $objectStateService)
    {
        $this->objectStateService = $objectStateService;
    }

    public function getCompletions()
    {
        return array(
            'objectStates' => $this->getObjectStateCompletions(),
            'objectStateGroups' => $this->getObjectStateGroupCompletions()
        );
    }

    protected function getObjectStateGroupCompletions()
    {
        $groups = $this->getObjectStateGroups();
        $completions = array();
        foreach ($groups as $group) {
            $names = $group->getNames();
            $completions[] = array(
                'id' => (int)$group->id,
                'name' => array_pop($names)
            );
        }

        return $completions;
    }

    protected function getObjectStateCompletions()
    {
        $groups = $this->getObjectStateGroups();
        $completions = array();
        foreach ($groups as $group) {
            $objectStates = $this->objectStateService->loadObjectStates($group);
            foreach ($objectStates as $objectState) {
                $names = $objectState->getNames();
                $completions[] = array(
                    'id' => (int)$objectState->id,
                    'name' => array_pop($names)
                );
            }
        }

        return $completions;
    }

    /**
     * @return ObjectStateGroup[]
     */
    protected function getObjectStateGroups()
    {
        return $this->objectStateService->loadObjectStateGroups();
    }
}
