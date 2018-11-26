<?php

namespace Caciobanu\Behat\DeprecationExtension\Tests\Call\Handler;

use Behat\Behat\Definition\Call\When;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Testwork\Environment\StaticEnvironment;
use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Testwork\Suite\GenericSuite;
use Caciobanu\Behat\DeprecationExtension\Call\Handler\RuntimeCallHandler;
use PHPUnit\Framework\TestCase;

/**
 * @author Catalin Ciobanu <caciobanu@gmail.com>
 */
class RuntimeCallHandlerTest extends TestCase
{
    public function testHandlerIsCalled()
    {
        $errorHandlerMock = $this->getMockBuilder('Caciobanu\Behat\DeprecationExtension\Error\Handler\DeprecationErrorHandler')
            ->getMock();

        $call = $this->getCall();
        $errorHandlerMock->expects($this->once())
            ->method('register')
            ->with($call, E_USER_DEPRECATED, 'TEST');

        $handler = new RuntimeCallHandler($errorHandlerMock);
        $handler->handleCall($call);
    }

    /**
     * @return DefinitionCall
     */
    private function getCall()
    {
        $suite = new GenericSuite('suite', array());
        $env = new StaticEnvironment($suite);
        $step = new StepNode('When', 'I test', array(), 4, 'When');
        $scenario = new ScenarioNode('Default mode', array(), array($step), 'Scenario', 3);
        $feature = new FeatureNode('Title', 'Description', array(), null, array($scenario), 'Feature', 'en', __FILE__, 1);
        $definition = new When('When I Test', function () { trigger_error('TEST', E_USER_DEPRECATED); });
        return new DefinitionCall($env, $feature, $step, $definition, array());
    }
}
