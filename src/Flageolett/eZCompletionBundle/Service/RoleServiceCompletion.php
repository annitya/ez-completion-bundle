<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\API\Repository\Values\User\Policy;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\Repository\Values\User\Role;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;
use Flageolett\eZCompletionBundle\Interfaces\CompletionInterface;
use eZ\Publish\Core\Repository\Repository;

class RoleServiceCompletion extends CompletionAbstract
{
    /** @var Repository */
    protected $repository;
    /** @var ConfigResolverInterface */
    protected $configResolver;
    /** @var \ezpKernelHandler */
    protected $legacyKernel;

    public function __construct(Repository $repository, ConfigResolverInterface $configResolver, \Closure $legacyKernel)
    {
        $this->repository = $repository;
        $this->configResolver = $configResolver;
        $this->legacyKernel = $legacyKernel();
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
        }, $this->fetchModules());

        $data = compact('module', 'role');
        $data['view'] = $this->fetchModuleViews($this->fetchModules());

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

    public function fetchLimitations()
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

    public function fetchModules()
    {
        return $this->configResolver->getParameter('ModuleSettings.ModuleList', 'module');
    }

    public function fetchModuleViews($modules)
    {
        return $this->legacyKernel->runCallback(function() use($modules)
        {
            $moduleRepositories = \eZModule::activeModuleRepositories();
            \eZModule::setGlobalPathList($moduleRepositories);

            $views = array();
            foreach ($modules as $moduleIdentifier) {
                $module = \eZModule::findModule($moduleIdentifier);
                if (!$module->hasAttribute('views')) {
                    continue;
                }

                $viewList = array_filter(array_keys($module->attribute('views')));
                foreach ($viewList as $view) {
                    $views[] = array(
                        'name' => $view,
                        'module' => $module->Name
                    );
                }
            }

            return $views;
        });
    }
}
