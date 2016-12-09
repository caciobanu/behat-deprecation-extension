Feature: Test default mode
  Background:
    When I generate error

  Scenario: Unsilenced
    When I generate silenced error
    And I generate silenced error

  Scenario: Silenced
    When I generate error
    And I generate error
