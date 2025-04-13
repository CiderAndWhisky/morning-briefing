Feature: Collecting article metadata
    As a news collector
    In order to have a list of available articles
    I will retrieve metadata about current news articles from the configured sources

    Scenario: No news sources configured
        When I update the available articles
        Then I will receive an error: "Configuration not found"

    Scenario: News sources are not downloaded because they were recently scanned
        Given I have a news source:
        | Name | RSS | Last_scanned | Rescrape_interval |


#    Scenario: News sources are downloaded, but no database exists
