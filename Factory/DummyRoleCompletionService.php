<?php

namespace Flageolett\eZCompletionBundle\Factory;

use Flageolett\eZCompletionBundle\Service\RoleServiceCompletion;

class DummyRoleCompletionService extends RoleServiceCompletion
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

    protected function fetchRoles()
    {
        return [];
    }

    protected function fetchLimitations()
    {
        return [];
    }

}