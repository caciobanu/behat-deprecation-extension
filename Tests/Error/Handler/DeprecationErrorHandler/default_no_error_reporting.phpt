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

User deprecated feature: 6x
    2x in Scenario: Silenced from file features/normal.feature on line 7
        1x in Step: When I generate error on line 8
        1x in Step: And I generate error on line 9
    2x in Scenario: Unsilenced from file features/normal_with_background_scenario.feature on line 5
        2x in Step: When I generate error on line 3
    2x in Scenario: Silenced from file features/normal_with_background_scenario.feature on line 9
        1x in Step: When I generate error on line 10
        1x in Step: And I generate error on line 11

Remaining deprecation notices (4)

User deprecated feature silenced: 4x
    2x in Scenario: Unsilenced from file features/normal.feature on line 3
        1x in Step: When I generate silenced error on line 4
        1x in Step: And I generate silenced error on line 5
    2x in Scenario: Unsilenced from file features/normal_with_background_scenario.feature on line 5
        1x in Step: When I generate silenced error on line 6
        1x in Step: And I generate silenced error on line 7

Legacy deprecation notices (8)

User deprecated feature silenced: 4x
    2x in Scenario: Unsilenced from file features/legacy_feature.feature on line 4
        1x in Step: When I generate silenced error on line 5
        1x in Step: And I generate silenced error on line 6
    2x in Scenario: Unsilenced from file features/legacy_scenario.feature on line 4
        1x in Step: When I generate silenced error on line 5
        1x in Step: And I generate silenced error on line 6

User deprecated feature: 4x
    2x in Scenario: Silenced from file features/legacy_feature.feature on line 8
        1x in Step: When I generate error on line 9
        1x in Step: And I generate error on line 10
    2x in Scenario: Silenced from file features/legacy_scenario.feature on line 9
        1x in Step: When I generate error on line 10
        1x in Step: And I generate error on line 11

Exit code: 0
