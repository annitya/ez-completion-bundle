<?php

namespace Flageolett\eZCompletionBundle\Factory;

use Flageolett\eZCompletionBundle\Service\ModuleViewCompletionService;
use Flageolett\eZCompletionBundle\Service\RoleServiceCompletion;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LegacyCompletionFactory
{
    public static function createModuleViewService(ContainerInterface $container)
    {
        if (!$container->has('ezpublish_legacy.kernel')) {
            return new DummyModuleViewCompletionService();
        }

        $configResolver = $container->get('ezpublish.config.resolver');
        $legacyKernelClosure = $container->get('ezpublish_legacy.kernel');

        /** @noinspection PhpParamsInspection */
        return new ModuleViewCompletionService($configResolver, $legacyKernelClosure);
    }

    public static function createRoleService(ContainerInterface $container)
    {
        if (!$container->has('ezpublish_legacy.kernel')) {
            return new DummyRoleCompletionService();
        }

        $repository = $container->get('ezpublish.api.repository');
        $configResolver = $configResolver = $container->get('ezpublish.config.resolver');

        return new RoleServiceCompletion($repository, $configResolver);
    }
}