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

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=ignore_file --out=default.log 2>/dev/null", $exitCode);

if ($exitCode === 1) {
    echo "Exit code: 1";
}

?>
--EXPECTF--

Exit code: 1
