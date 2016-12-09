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

passthru("php -d error_reporting=32767 " . $behat . "/vendor/bin/behat --profile=default --out=default.log 2>/dev/null", $exitCode);

if ($exitCode === 1) {
    echo "Exit code: 1";
}

?>
--EXPECTF--

Unsilenced deprecation notices (3)

User deprecated feature: 3x
    1x in Scenario: Silenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/normal.feature on line 7
        1x in Step: When I generate error on line 8
    2x in Scenario: Unsilenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/normal_with_background_scenario.feature on line 5
        2x in Step: When I generate error on line 3

Remaining deprecation notices (2)

User deprecated feature silenced: 2x
    2x in Scenario: Unsilenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/normal.feature on line 3
        1x in Step: When I generate silenced error on line 4
        1x in Step: And I generate silenced error on line 5

Legacy deprecation notices (6)

User deprecated feature silenced: 4x
    2x in Scenario: Unsilenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/legacy_feature.feature on line 4
        1x in Step: When I generate silenced error on line 5
        1x in Step: And I generate silenced error on line 6
    2x in Scenario: Unsilenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/legacy_scenario.feature on line 4
        1x in Step: When I generate silenced error on line 5
        1x in Step: And I generate silenced error on line 6

User deprecated feature: 2x
    1x in Scenario: Silenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/legacy_feature.feature on line 8
        1x in Step: When I generate error on line 9
    1x in Scenario: Silenced from file /home/travis/build/caciobanu/behat-deprecation-extension/features/legacy_scenario.feature on line 9
        1x in Step: When I generate error on line 10

Exit code: 1
