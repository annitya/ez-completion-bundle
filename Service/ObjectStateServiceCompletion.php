<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\ObjectStateService;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectState;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;

class ObjectStateServiceCompletion extends CompletionAbstract
{
    /** @var ObjectStateService */
    protected $objectStateService;

    public function __construct(ObjectStateService $objectStateService)
    {
        $this->objectStateService = $objectStateService;
    }

    protected function getDataSource()
    {
        return array(
            'objectstate' => $this->fetchObjectStates(),
            'objectstategroup' => $this->fetchObjectStateGroups()
        );
    }

    protected function fetchObjectStates()
    {
        $groups = $this->objectStateService->loadObjectStateGroups();
        $completions = array();
        foreach ($groups as $group) {
            $completions = array_merge(array_map(function(ObjectState $objectState)
            {
                return array(
                    'id' => (int)$objectState->id,
                    'name' => self::getTranslatedName($objectState, $this->language)
                );
            }, $this->objectStateService->loadObjectStates($group)), $completions);
        }

        return $completions;
    }

    protected function fetchObjectStateGroups()
    {
        return array_map(function(ObjectStateGroup $objectStateGroup)
        {
            return array(
                'id' => (int)$objectStateGroup->id,
                'name' => self::getTranslatedName($objectStateGroup, $this->language)
            );
        }, $this->objectStateService->loadObjectStateGroups());
    }
}
