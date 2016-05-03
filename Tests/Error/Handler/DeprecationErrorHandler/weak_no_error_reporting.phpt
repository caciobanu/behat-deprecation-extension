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

passthru($behat . "/vendor/bin/behat --profile=weak_no_error_reporting --out=weak_no_error_reporting.log", $exitCode);

if ($exitCode === 0) {
    echo "\nExit code: 0";
}

?>
--EXPECTF--

Unsilenced deprecation notices (2)

Remaining deprecation notices (2)

Legacy deprecation notices (8)

Exit code: 0
