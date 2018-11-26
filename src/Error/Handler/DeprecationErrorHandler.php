<?php

/*
 * This file is part of the Caciobanu\Behat\DeprecationExtension package.
 * (c) Catalin Ciobanu <caciobanu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Caciobanu\Behat\DeprecationExtension\Error\Handler;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Gherkin\Node\StepNode;
use Behat\Testwork\Call\Call;

/**
 * @author Catalin Ciobanu <caciobanu@gmail.com>
 */
class DeprecationErrorHandler
{
    const TRACE_INDEX = 4;
    const MODE_WEAK = 'weak';

    private static $shutdownFunctionIsRegistered = false;

    private $deprecations = array(
        'unsilencedCount' => 0,
        'remainingCount' => 0,
        'legacyCount' => 0,
        'unsilenced' => array(),
        'remaining' => array(),
        'legacy' => array(),
    );

    /**
     * The following reporting modes are supported:
     * - use null to display the deprecation report without making the test suite fail;
     * - use "weak" to hide the deprecation report but keep a global count;
     * - use a number to define the upper bound of allowed deprecations,
     *   making the test suite fail whenever more notices are triggered.
     *
     * @var int|string|null The reporting mode.
     */
    private $mode;

    /**
     * @param int|string|null $mode The reporting mode.
     */
    public function __construct($mode = null)
    {
        $this->mode = $mode;
    }

    /**
     * @param Call $call
     * @param integer $level
     * @param string $message
     */
    public function register(Call $call, $level, $message)
    {
        if (E_USER_DEPRECATED !== $level) {
            return;
        }

        $trace = debug_backtrace();

        $group = 'remaining';
        if (0 !== error_reporting()) {
            $group = 'unsilenced';
        }

        if ($call instanceof DefinitionCall) {
            $scenario = $this->detectScenario($call->getFeature(), $call->getStep());

            if ($call->getFeature()->hasTag('legacy') || $scenario->hasTag('legacy')) {
                $group = 'legacy';
            }
        }

        if (isset($trace[self::TRACE_INDEX])) {
            if (isset($trace[self::TRACE_INDEX]['object']) || isset($trace[self::TRACE_INDEX]['class'])) {
                $class = isset($trace[self::TRACE_INDEX]['object']) ? get_class($trace[self::TRACE_INDEX]['object']) : $trace[self::TRACE_INDEX]['class'];
                $method = $trace[self::TRACE_INDEX]['function'];

                if (!isset($this->deprecations[$group][$message][$class . '::' . $method])) {
                    $this->deprecations[$group][$message][$class . '::' . $method] = 0;
                }

                $this->deprecations[$group][$message][$class . '::' . $method]++;
            }
        }

        if (!isset($this->deprecations[$group][$message]['count'])) {
            $this->deprecations[$group][$message]['count'] = 0;
        }
        $this->deprecations[$group][$message]['count']++;

        $this->deprecations[$group . 'Count']++;

        $this->registerShutdownFunction();
    }

    /**
     * @param FeatureNode $featureNode
     * @param StepNode $stepNode
     * @return ScenarioInterface|null
     */
    private function detectScenario(FeatureNode $featureNode, StepNode $stepNode)
    {
        foreach ($featureNode->getScenarios() as $scenario) {
            $steps = $scenario->getSteps();
            if ($featureNode->hasBackground()) {
                $steps = array_merge($featureNode->getBackground()->getSteps(), $steps);
            }
            foreach ($steps as $step) {
                if ($step->getLine() === $stepNode->getLine()) {
                    return $scenario;
                }
            }
        }

        return null;
    }

    /**
     * Register shutdown function
     */
    private function registerShutdownFunction()
    {
        if (!self::$shutdownFunctionIsRegistered) {
            register_shutdown_function(array($this, 'displayDeprecations'));
            self::$shutdownFunctionIsRegistered = true;
        }
    }

    /**
     * Display deprecations caught
     */
    public function displayDeprecations()
    {
        $newLine = false;
        foreach (array('unsilenced', 'remaining', 'legacy') as $group) {
            if ($this->deprecations[$group . 'Count']) {
                echo "\n", $this->colorize(
                    sprintf('%s deprecation notices (%d)', ucfirst($group), $this->deprecations[$group . 'Count']),
                    'legacy' !== $group
                ), "\n";

                if (self::MODE_WEAK === $this->mode) {
                    continue;
                }
                $newLine = true;

                foreach ($this->deprecations[$group] as $msg => $notices) {
                    echo "\n", rtrim($msg, '.'), ': ', $notices['count'], "x\n";

                    unset($notices['count']);

                    foreach ($notices as $method => $count) {
                        echo '    ', $count, 'x in ', preg_replace('/(.*)\\\\(.*?::.*?)$/', '$2 from $1', $method), "\n";
                    }
                }
            }
        }

        if ($newLine) {
            echo "\n";
        }

        if (null !== $this->mode
            && self::MODE_WEAK !== $this->mode
            && $this->mode < $this->deprecations['unsilencedCount'] + $this->deprecations['remainingCount']
        ) {
            exit(1);
        }
    }

    /**
     * @param string $str
     * @param boolean $red
     * @return string
     */
    private function colorize($str, $red)
    {
        if ($this->hasColorSupport()) {
            $color = $red ? '41;37' : '43;30';

            return "\x1B[{$color}m{$str}\x1B[0m";
        }

        return $str;
    }

    /**
     * @return bool
     */
    private function hasColorSupport()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return defined('STDOUT') && function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }
}
