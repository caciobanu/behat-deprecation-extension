<?php

/*
 * This file is part of the Caciobanu\Behat\DeprecationExtension package.
 * (c) Catalin Ciobanu <caciobanu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Caciobanu\Behat\DeprecationExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Catalin Ciobanu <caciobanu@gmail.com>
 */
class DeprecationExtension implements Extension
{

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $defintion = $container->getDefinition('call.call_handler.runtime');
        $defintion->setClass('Caciobanu\Behat\DeprecationExtension\Call\Handler\RuntimeCallHandler');

        $args = $defintion->getArguments();
        array_unshift($args, new Reference('caciobanu.deprecation_extension.deprecation_error_handler'));
        $defintion->setArguments($args);
    }

    /**
     * @inheritdoc
     */
    public function getConfigKey()
    {
        return 'caciobanu_deprecation_extension';
    }

    /**
     * @inheritdoc
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * @inheritdoc
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('whitelist')
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('mode')
                    ->defaultValue(null)
                    ->validate()
                        ->ifTrue(function ($value) {
                            if (!is_null($value) && !is_int($value) && 'weak' !== $value) {
                                return true;
                            }

                            return false;
                        })
                        ->thenInvalid('mode can be "weak" or integer only')
                ->end()
            ->end()
        ;
    }

    /**
     * @inheritdoc
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('caciobanu.deprecation_extension.mode', $config['mode']);
        $container->setParameter('caciobanu.deprecation_extension.whitelist', isset($config['whitelist']) ? $config['whitelist'] : array());

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
