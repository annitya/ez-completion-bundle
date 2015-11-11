<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 21.12.14
 */

namespace Flageolett\eZCompletionBundle\Service;

use Flageolett\eZCompletionBundle\Abstracts\DependentCompletionAbstract;
use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\Core\MVC\ConfigResolverInterface;

class ModuleViewCompletionService extends DependentCompletionAbstract
{
    /** @var ConfigResolverInterface */
    protected $configResolver;
    /** @var \ezpKernelHandler */
    protected $legacyKernel;

    public function __construct(ConfigResolverInterface $configResolver, \Closure $legacyKernel)
    {
        $this->configResolver = $configResolver;
        $this->legacyKernel = $legacyKernel();
    }

    protected function getDataSource()
    {
        $modules = $this->configResolver->getParameter('ModuleSettings.ModuleList', 'module');
        return array('view' => $this->fetchModuleViews($modules));
    }

    protected function fetchModuleViews($modules)
    {
        return $this->legacyKernel->runCallback(function() use ($modules) {
            $moduleRepositories = \eZModule::activeModuleRepositories();
            \eZModule::setGlobalPathList($moduleRepositories);

            $views = array();
            foreach ($modules as $moduleIdentifier) {
                $module = \eZModule::findModule($moduleIdentifier);
                if (!$module || !$module->hasAttribute('views')) {
                    continue;
                }

                $viewList = array_filter(array_keys($module->attribute('views')));
                foreach ($viewList as $view) {
                    $views[$module->Name][] = array('name' => $view);
                }
            }

            return $views;
        });
    }
}
