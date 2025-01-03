<?php

/*
 * This file is part of the Caciobanu\Behat\DeprecationExtension package.
 * (c) Catalin Ciobanu <caciobanu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Caciobanu\Behat\DeprecationExtension\Call\Handler;

use Behat\Testwork\Call\Call;
use Behat\Testwork\Call\CallResult;
use Behat\Testwork\Call\Exception\CallErrorException;
use Behat\Testwork\Call\Handler\CallHandler;
use Caciobanu\Behat\DeprecationExtension\Error\Handler\DeprecationErrorHandler;
use Exception;

/**
 * Handles calls in the current runtime.
 *
 * @author Catalin Ciobanu <caciobanu@gmail.com>
 */
final class RuntimeCallHandler implements CallHandler
{
    /**
     * @var integer
     */
    private $errorReportingLevel;

    /**
     * @var bool
     */
    private $obStarted = false;

    /**
     * @var DeprecationErrorHandler
     */
    private $errorHandler;

    /**
     * @var Call
     */
    private $call;

    /**
     * Initializes executor.
     *
     * @param DeprecationErrorHandler $errorHandler
     * @param int $errorReportingLevel
     */
    public function __construct(DeprecationErrorHandler $errorHandler, $errorReportingLevel = E_ALL)
    {
        $this->errorHandler = $errorHandler;
        $this->errorReportingLevel = $errorReportingLevel;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsCall(Call $call)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function handleCall(Call $call)
    {
        $this->startErrorAndOutputBuffering($call);
        $result = $this->executeCall($call);
        $this->stopErrorAndOutputBuffering();

        return $result;
    }

    /**
     * Used as a custom error handler when step is running.
     *
     * @see set_error_handler()
     *
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @return Boolean
     *
     * @throws CallErrorException
     */
    public function handleError($level, $message, $file, $line)
    {
        $this->errorHandler->register($this->call, $level, $message);

        if ($this->errorLevelIsNotReportable($level)) {
            return false;
        }

        $errorReporting = $this->call->getErrorReportingLevel() ? : $this->errorReportingLevel;
        /**
         * Because we always catch E_USER_DEPRECATED we need to verify
         * that this level is in the initial error reporting level.
         */
        if (($level | $errorReporting) == $errorReporting) {
            throw new CallErrorException($level, $message, $file, $line);
        }

        return false;
    }

    /**
     * Executes single call.
     *
     * @param Call $call
     *
     * @return CallResult
     */
    private function executeCall(Call $call)
    {
        $callable = $call->getBoundCallable();
        $arguments = $call->getArguments();

        $return = $exception = null;

        try {
            $this->call = $call;
            $return = call_user_func_array($callable, array_values($arguments));
        } catch (Exception $caught) {
            $exception = $caught;
        }

        $stdOud = $this->getBufferedStdOut();

        return new CallResult($call, $return, $exception, $stdOud);
    }

    /**
     * Returns buffered stdout.
     *
     * @return null|string
     */
    private function getBufferedStdOut()
    {
        return ob_get_length() ? ob_get_contents() : null;
    }

    /**
     * Starts error handler and stdout buffering.
     */
    private function startErrorAndOutputBuffering(Call $call)
    {
        $errorReporting = $call->getErrorReportingLevel() ? : $this->errorReportingLevel;
        set_error_handler(array($this, 'handleError'), $errorReporting | E_USER_DEPRECATED);
        $this->obStarted = ob_start();
    }

    /**
     * Stops error handler and stdout buffering.
     */
    private function stopErrorAndOutputBuffering()
    {
        if ($this->obStarted) {
            ob_end_clean();
        }
        restore_error_handler();
    }

    /**
     * Checks if provided error level is not reportable.
     *
     * @param integer $level
     *
     * @return Boolean
     */
    private function errorLevelIsNotReportable($level)
    {
        return !(error_reporting() & $level);
    }
}
