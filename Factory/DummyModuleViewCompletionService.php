<?php

namespace Flageolett\eZCompletionBundle\Factory;

use Flageolett\eZCompletionBundle\Service\ModuleViewCompletionService;

class DummyModuleViewCompletionService extends ModuleViewCompletionService
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct() {}

    public function setConfig($config)
    {
        $this->sources = [];
    }

    protected function getDataSource()
    {
        return [];
    }

    protected function fetchModuleViews($modules)
    {
        return [];
    }
}
