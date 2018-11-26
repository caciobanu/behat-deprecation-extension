<?php

namespace Caciobanu\Behat\DeprecationExtension\Tests\Deprecated;

class DeprecatedClass
{
    public function deprecatedMethodSilenced()
    {
        @trigger_error("Method 'deprecatedMethodSilenced' is deprecated.", E_USER_DEPRECATED);
    }

    public function deprecatedMethodUnsilenced()
    {
        trigger_error("Method 'deprecatedMethodUnsilenced' is deprecated.", E_USER_DEPRECATED);
    }
}
