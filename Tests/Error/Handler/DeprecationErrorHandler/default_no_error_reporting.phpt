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

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=default_no_error_reporting --out=default_no_error_reporting.log 2>/dev/null", $exitCode);

if ($exitCode === 0) {
    echo "Exit code: 0";
}

?>
--EXPECTF--

Unsilenced deprecation notices (6)

Method 'deprecatedMethodUnsilenced' is deprecated: 6x
    6x in DeprecatedCaller::callDeprecatedMethodUnsilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Remaining deprecation notices (4)

Method 'deprecatedMethodSilenced' is deprecated: 4x
    4x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Legacy deprecation notices (8)

Method 'deprecatedMethodSilenced' is deprecated: 4x
    4x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Method 'deprecatedMethodUnsilenced' is deprecated: 4x
    4x in DeprecatedCaller::callDeprecatedMethodUnsilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Exit code: 0
