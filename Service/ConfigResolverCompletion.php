<?php

namespace Flageolett\eZCompletionBundle\Service;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use Flageolett\eZCompletionBundle\Abstracts\CompletionAbstract;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigResolverCompletion extends CompletionAbstract
{
    /** @var ContainerInterface */
    protected $container;
    /** @var string */
    protected $namespacePart;

    public function __construct(ContainerInterface $container, $defaultNamespace)
    {
        $this->container = $container;
        $this->namespacePart = $defaultNamespace . '.';
    }

    protected function getDataSource()
    {
        $reflection = new \ReflectionObject($this->container);
        $property = $reflection->getProperty('parameters');
        $property->setAccessible(true);

        $parameters = array_keys($property->getValue($this->container));
        $settingGroups = $this->getSettingGroups();

        $eZParameters = array();
        foreach ($parameters as $parameter) {
            // Lets skip the standard parameters.
            if (strpos($parameter, $this->namespacePart) !== 0) {
                continue;
            }
            $parameter = str_replace($this->namespacePart, '', $parameter);
            foreach ($settingGroups as $group) {
                $groupPart = $group . '.';
                if (strpos($parameter, $groupPart) === 0) {
                    $parameter = str_replace($groupPart, '', $parameter);
                    continue;
                }
            }

            $eZParameters[$parameter] = array('name' => $parameter);
        }

        return array('parameters' => array_values($eZParameters));
    }

    /**
     * @return array
     */
    protected function getSettingGroups()
    {
        $siteaccesses = $this->container->getParameter('ezpublish.siteaccess.groups_by_siteaccess');
        $settingGroups = array();
        foreach ($siteaccesses as $siteaccess => $groupList) {
            $settingGroups[] = $siteaccess;
            $settingGroups = array_merge($settingGroups, $groupList);
        }
        $settingGroups[] = ConfigResolver::SCOPE_DEFAULT;
        $settingGroups[] = ConfigResolver::SCOPE_GLOBAL;

        return array_unique($settingGroups);
    }
}