Feature: Configurable promptable options

  Scenario: An option's type will ensure the input is transformed accordingly
    When I run the IntegerCommand and input "7"
    Then I should see "Type age: integer"

  Scenario: If an option is not required and no default is configured, its value will be null
    When I run the NotRequiredCommand and input " "
    Then I should see "Type name: NULL"
    And I should see "Length name: 0"

  Scenario: If an option is not required and a default is configured, its value will be the default that was set
    When I run the DefaultSetCommand and input " "
    Then I should see "Type input: string"
    And I should see "Input: My default value"

  Scenario: If an option is given and it cannot be transformed correctly, the user will be prompted for a new value
    When I run the IntegerCommand with the option "--age=hello" and input " "
    Then I should see "[ERROR] A valid integer is required."
    And I should see "Your age:"
