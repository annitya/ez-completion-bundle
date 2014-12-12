<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 27.11.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\API\Repository\Values\User\Policy;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Flageolett\eZCompletionBundle\Interfaces\CompletionInterface;
use eZ\Publish\Core\Repository\Repository;

class RoleCompletion implements CompletionInterface
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

    public function getCompletions()
    {
        $completions =  $this->repository->sudo(function()
        {
            $roleList = $this->repository->getRoleService()->loadRoles();
            $roles = array();
            $limitations = array();
            foreach ($roleList as $role) {
                /** @var Policy $policy */
                foreach ($role->policies as $policy) {
                    foreach ($policy->limitations as $limitation) {
                        /** @var Limitation $limitation */
                        $limitations[] = $limitation->getIdentifier();
                    }
                }
                $roles[] = array(
                    'id' => (int)$role->id,
                    'identifier' => $role->identifier,
                );
            }

            $limitationReflection = new \ReflectionClass('\eZ\Publish\API\Repository\Values\User\Limitation');
            $constants = $limitationReflection->getConstants();
            $constants = array_values($constants);

            $limitations = array_merge($limitations, $constants);
            $limitations = array_unique($limitations);
            $limitations = array_map(function ($identifier) {
                return array('identifier' => $identifier);
            }, $limitations);
            $limitations = array_values($limitations);

            return compact('roles', 'limitations');
        });

        $completions += $this->getModuleCompletions();
        return $completions;
    }

    public function getModuleCompletions()
    {
        $moduleList = $this->configResolver->getParameter('ModuleSettings.ModuleList', 'module');
        $modules = array_map(function($module)
        {
            return array('identifier' => $module);
        }, $moduleList);
        $modules = $this->legacyKernel->runCallback(function() use ($modules)
        {
            $moduleRepositories = \eZModule::activeModuleRepositories();
            \eZModule::setGlobalPathList($moduleRepositories);
            foreach ($modules as $key => $module) {
                $module = \eZModule::findModule($module['identifier']);
                if (!$module->hasAttribute('views')) {
                    continue;
                }
                $views = $module->attribute('views');
                $modules[$key]['views'] = array_keys($views);
            }

            return $modules;
        });

        return compact('modules');
    }
}
