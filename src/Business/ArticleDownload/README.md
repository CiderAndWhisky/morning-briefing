# Article Download

This module will download the RSS feeds based on update interval (no reloading faster than the update interval allows). 

The metadata received will be stored in a PSQL database with this format:
| Column   | Type         | Description                                                  |
| -------- | ------------ | ------------------------------------------------------------ |
| GUID     | CHAR(32)     | A unique ID (UUID7)                                          |
| Region   | VARCHAR(255) | The region from the yaml path, p.ex. es/regional/mad/elmolar |
| Source   | VARCHAR(255) | The name of the news source (name field of the source yaml)  |
| Category | VARCHAR(255) | The category, as retrieved from the article                  |
| Title    | VARCHAR(255) | The article title                                            |
| Link     | TEXT         | The link to the article content                              |