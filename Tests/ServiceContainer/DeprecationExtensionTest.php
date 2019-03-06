<?php

namespace Caciobanu\Behat\DeprecationExtension\Tests\ServiceContainer;

use Behat\Testwork\ServiceContainer\Configuration\ConfigurationTree;
use Caciobanu\Behat\DeprecationExtension\ServiceContainer\DeprecationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Catalin Ciobanu <caciobanu@gmail.com>
 */
class DeprecationExtensionTest extends TestCase
{
    public function testProcess()
    {
        $definition = new Definition();
        $definition->setClass('test');
        $definition->addArgument(E_ALL);

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setDefinition('call.call_handler.runtime', $definition);

        $extension = new DeprecationExtension();
        $extension->process($containerBuilder);

        $this->assertEquals('Caciobanu\Behat\DeprecationExtension\Call\Handler\RuntimeCallHandler' ,$definition->getClass());
        $this->assertEquals('caciobanu.deprecation_extension.deprecation_error_handler', (string) $definition->getArgument(0));
        $this->assertEquals(E_ALL, $definition->getArgument(1));
    }

    public function testLoad()
    {
        $containerBuilder = new ContainerBuilder();

        $extension = new DeprecationExtension();
        $extension->load($containerBuilder, array('mode' => 'weak'));

        $definition = $containerBuilder->getDefinition('caciobanu.deprecation_extension.deprecation_error_handler');

        $this->assertEquals('Caciobanu\Behat\DeprecationExtension\Error\Handler\DeprecationErrorHandler' ,$definition->getClass());
        $this->assertEquals('%caciobanu.deprecation_extension.mode%', (string) $definition->getArgument(0));
        $this->assertEquals('%caciobanu.deprecation_extension.ignore%', (string) $definition->getArgument(1));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigureInvalidValue()
    {
        $configurationTree = new ConfigurationTree();
        $tree = $configurationTree->getConfigTree(array(new DeprecationExtension()));

        $processor = new Processor();
        $processor->process($tree, array(
            'testwork' => array(
                'caciobanu_deprecation_extension' => array(
                    'mode' => 'test',
                ),
            ),
        ));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigureInvalidIgnoreValue()
    {
        $configurationTree = new ConfigurationTree();
        $tree = $configurationTree->getConfigTree(array(new DeprecationExtension()));

        $processor = new Processor();
        $processor->process($tree, array(
            'testwork' => array(
                'caciobanu_deprecation_extension' => array(
                    'mode' => 'weak',
                    'ignore' => array('hello'),
                ),
            ),
        ));
    }

    /**
     * @dataProvider configValueProvider
     */
    public function testConfigure($mode)
    {
        $configurationTree = new ConfigurationTree();
        $tree = $configurationTree->getConfigTree(array(new DeprecationExtension()));

        $processor = new Processor();
        $config = $processor->process($tree, array(
            'testwork' => array(
                'caciobanu_deprecation_extension' => array(
                    'mode' => $mode,
                ),
            ),
        ));

        $this->assertEquals(array('caciobanu_deprecation_extension' => array('mode' => $mode, 'ignore' => array())), $config);
    }

    /**
     * @dataProvider configIgnoreValueProvider
     */
    public function testConfigureIgnore($ignore)
    {
        $configurationTree = new ConfigurationTree();
        $tree = $configurationTree->getConfigTree(array(new DeprecationExtension()));

        $processor = new Processor();
        $config = $processor->process($tree, array(
            'testwork' => array(
                'caciobanu_deprecation_extension' => array(
                    'mode' => 'weak',
                    'ignore' => $ignore
                ),
            ),
        ));

        $this->assertEquals(array('caciobanu_deprecation_extension' => array('mode' => 'weak', 'ignore' => $ignore)), $config);
    }

    public function configValueProvider()
    {
        return array(
            array(null),
            array('weak'),
            array(0),
            array(10),
            array(100),
        );
    }

    public function configIgnoreValueProvider()
    {
        return array(
            array(array(array('file' => 'file'))),
            array(array(array('message' => 'message'))),
            array(array(array('file' => 'file', 'message' => 'message'), array('file' => 'file', 'message' => 'message'))),
        );
    }
}
