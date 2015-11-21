<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 20.11.15
 * Time: 20:46
 */

namespace AxS\ConfigBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AxSConfigExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('axs_config.use_groups', $config['use_groups']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('admin_config.yml');

        if (!empty($config['use_groups'])) {
            $loader->load('admin_config_group.yml');
        }
    }
}