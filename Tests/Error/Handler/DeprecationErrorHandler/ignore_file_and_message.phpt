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

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=ignore_file_and_message --out=default.log 2>/dev/null", $exitCode);

if ($exitCode === 1) {
    echo "Exit code: 1";
}

?>
--EXPECTF--

Remaining deprecation notices (2)

Method 'deprecatedMethodSilenced' is deprecated: 2x
    2x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Legacy deprecation notices (4)

Method 'deprecatedMethodSilenced' is deprecated: 4x
    4x in DeprecatedCaller::callDeprecatedMethodSilenced from Caciobanu\Behat\DeprecationExtension\Tests\Deprecated

Exit code: 1
