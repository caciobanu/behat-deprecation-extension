<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @When I generate silenced error
     */
    public function whenIGenerateSilencedError()
    {
        @trigger_error("User deprecated feature silenced.", E_USER_DEPRECATED);
    }

    /**
     * @When I generate error
     */
    public function whenIGenerateError()
    {
        trigger_error("User deprecated feature.", E_USER_DEPRECATED);
    }
}
