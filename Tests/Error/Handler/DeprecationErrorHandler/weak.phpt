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

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=weak --out=weak.log 2>/dev/null", $exitCode);

if ($exitCode === 1) {
    echo "\nExit code: 1";
}

?>
--EXPECTF--

Unsilenced deprecation notices (1)

Remaining deprecation notices (2)

Legacy deprecation notices (6)

Exit code: 1
