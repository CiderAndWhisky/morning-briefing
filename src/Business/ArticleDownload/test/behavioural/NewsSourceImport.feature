Feature: Import news sources from YAML to database
    As a system administrator
    In order to have news sources available for article collection
    I will import news source configurations from YAML files into the database

    Background:
        Given I have an empty news_sources table
        And I have an empty news_sources_meta table

    Scenario: Import a single news source
        Given I have a news source YAML file at "es/national/elmundo.yaml"
        When I import news sources
        Then the news source should be imported into the news_sources table
        And the news source meta should be created in the news_sources_meta table
        And the source_id should be a hash of "es/national/elmundo.yaml"
        And the content_hash should match the YAML file content

    Scenario: Update an existing news source
        Given I have a news source YAML file at "es/national/elmundo.yaml"
        And the news source is already imported
        When I modify the YAML file
        And I import news sources
        Then the news source should be updated in the news_sources table
        And the content_hash should be updated
        And the last_updated timestamp should be updated

    Scenario: Skip unchanged news sources
        Given I have multiple news source YAML files
        And all news sources are already imported
        And the content hashes match
        When I import news sources
        Then no news sources should be updated
        And no news source meta should be updated

    Scenario: Handle invalid YAML files
        Given I have an invalid YAML file at "es/national/invalid.yaml"
        When I import news sources
        Then I should receive an error for the invalid file
        And valid news sources should still be imported
        And the error should be logged

    Scenario: Handle missing required fields
        Given I have a YAML file missing required fields at "es/national/incomplete.yaml"
        When I import news sources
        Then I should receive an error for the incomplete file
        And valid news sources should still be imported
        And the error should be logged 