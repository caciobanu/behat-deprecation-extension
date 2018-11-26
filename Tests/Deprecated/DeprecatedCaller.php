<?php

namespace Caciobanu\Behat\DeprecationExtension\Tests\Deprecated;

class DeprecatedCaller
{
    public function callDeprecatedMethodSilenced()
    {
        $tmp = new DeprecatedClass;
        $tmp->deprecatedMethodSilenced();
    }

    public function callDeprecatedMethodUnsilenced()
    {
        $tmp = new DeprecatedClass;
        $tmp->deprecatedMethodUnsilenced();
    }
}
