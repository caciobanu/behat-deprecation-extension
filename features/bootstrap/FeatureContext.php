<?php

use Behat\Behat\Context\Context;
use Caciobanu\Behat\DeprecationExtension\Tests\Deprecated\DeprecatedCaller;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @When I generate silenced error
     */
    public function whenIGenerateSilencedError()
    {
        $tmp = new DeprecatedCaller();
        $tmp->callDeprecatedMethodSilenced();
    }

    /**
     * @When I generate error
     */
    public function whenIGenerateError()
    {

        $tmp = new DeprecatedCaller();
        $tmp->callDeprecatedMethodUnsilenced();
    }
}
