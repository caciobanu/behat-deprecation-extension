Feature: Test default mode

  @legacy
  Scenario: Unsilenced
    When I generate silenced error
    And I generate silenced error

  @legacy
  Scenario: Silenced
    When I generate error
    And I generate error
