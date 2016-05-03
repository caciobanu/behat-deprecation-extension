@legacy
Feature: Test default mode

  Scenario: Unsilenced
    When I generate silenced error
    And I generate silenced error

  Scenario: Silenced
    When I generate error
    And I generate error
