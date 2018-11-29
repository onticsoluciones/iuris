<?php

namespace Ontic\Iuris\Service\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContainerFactory
{
    /**
     * @return ContainerInterface
     * @throws \Exception
     */
    public static function get()
    {
        $rootDir = __DIR__ . '/../../../';
        $container = new ContainerBuilder();
        $container->setParameter('root_dir', $rootDir);
        $container->setParameter('plugin_dir', $rootDir . '/src/Plugin');
        $container->setParameter('configuration_file', $rootDir . '/parameters.yml');
        $loader = new YamlFileLoader($container, new FileLocator($rootDir));
        $loader->load('services.yml');
        $container->compile();
        
        return $container;
    }
}