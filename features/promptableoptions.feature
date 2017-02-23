Feature: Promptable options on Symfony Console commands
  In order to avoid issues when running commands and forgetting options
  As a developer
  I want to be prompted to enter options I'd forgotten to specify when first running a command

  Scenario: Promptable options are able to be entered after the command has run
    When I run the PromptableCommand and input "Matt"
    Then I should see "Hello Matt!"

  Scenario: Multiple promptable options are able to be entered after the command has run
    When I run the PromptableCommand and input "Matt"
    Then I should see "Hello Matt!"

  Scenario: Promptable options are not asked for if provided when the command is first run
    When I run the PromptableCommand with the option "--name=Matt"
    Then I should see "Hello Matt!"
    And I should not see "Your Name"

  Scenario: Other options are not affected by the promptable options given
    When I run the PromptableCommand with the option "--age=29" and input "Matt"
    Then I should see "Hello Matt!"
    And I should see "You are 29 years old"

  Scenario: Running in non-interactive mode causes the command to exit out
    When I run the PromptableCommand in "non-interactive" mode
    Then I should see "Cannot prompt for name, command is running in non-interactive mode"
    And the command should not have completed successfully
