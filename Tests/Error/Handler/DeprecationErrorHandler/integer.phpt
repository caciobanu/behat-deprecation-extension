--TEST--
Test DeprecationErrorHandler in weak mode
--FILE--
<?php

putenv('ANSICON');
putenv('ConEmuANSI');
putenv('TERM');

$behat = __DIR__;
while (!file_exists($behat . '/vendor/bin/behat')) {
    $behat = dirname($behat);
}

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=integer --out=integer.log 2>/dev/null", $exitCode);

if ($exitCode === 1) {
    echo "Exit code: 1";
}

?>
--EXPECTF--

Unsilenced deprecation notices (3)

Method 'deprecatedMethodUnsilenced' is deprecated: 3x
    3x in DeprecatedCaller::callDeprecatedMethodUnsilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Remaining deprecation notices (2)

Method 'deprecatedMethodSilenced' is deprecated: 2x
    2x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Legacy deprecation notices (6)

Method 'deprecatedMethodSilenced' is deprecated: 4x
    4x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Method 'deprecatedMethodUnsilenced' is deprecated: 2x
    2x in DeprecatedCaller::callDeprecatedMethodUnsilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Exit code: 1
