<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\API\Repository\Values\User\Policy;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\Repository\Repository;
use eZ\Publish\Core\Repository\Values\User\Role;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;
use eZ\Publish\Api\Repository\Repository as RepositoryInterface;

class RoleServiceCompletion extends CompletionAbstract
{
    /** @var Repository */
    protected $repository;
    /** @var ConfigResolverInterface */
    protected $configResolver;

    public function __construct(RepositoryInterface $repository, ConfigResolverInterface $configResolver)
    {
        $this->repository = $repository;
        $this->configResolver = $configResolver;
    }

    protected function getDataSource()
    {
        $role = array_map(function(Role $role)
        {
            return array(
                'id' => (int)$role->id,
                'identifier' => $role->identifier
            );
        }, $this->fetchRoles());

        $module = array_map(function($module) {
            return array('identifier' => $module);
        }, $this->configResolver->getParameter('ModuleSettings.ModuleList', 'module'));

        $data = compact('module', 'role');
        $data['limitation'] = $this->fetchLimitations();

        return $data;
    }

    /**
     * @return Role[]
     *
     * @throws \Exception
     */
    protected function fetchRoles()
    {
        return $this->repository->sudo(function()
        {
            return $this->repository->getRoleService()->loadRoles();
        });
    }

    protected function fetchLimitations()
    {
        $policies = array();
        foreach ($this->fetchRoles() as $role) {
            $policies = array_merge($role->getPolicies(), $policies);
        }

        $limitations = array();
        /** @var Policy $policy */
        foreach ($policies as $policy) {
            foreach ($policy->getLimitations() as $limitation) {
                $limitations[] = $limitation->getIdentifier();
            }
        }

        $limitationReflection = new \ReflectionClass('\eZ\Publish\API\Repository\Values\User\Limitation');
        $constants = $limitationReflection->getConstants();
        $constants = array_values($constants);

        $limitations = array_merge($limitations, $constants);
        $limitations = array_unique($limitations);
        $limitations = array_map(function($identifier) {
            return array('identifier' => $identifier);
        }, $limitations);

        return array_values($limitations);
    }
}
