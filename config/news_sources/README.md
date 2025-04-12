# News Sources Configuration

This directory contains the configuration for news sources used by the Morning Briefing system.

## Directory Structure

```
config/news_sources/
├── de/                      # Country level (Germany)
│   ├── national/           # National sources
│   │   ├── tagesschau.yaml
│   │   ├── spiegel.yaml
│   │   ├── faz.yaml
│   │   └── sz.yaml
│   └── regional/           # Regional sources
│       └── by/            # Bavaria
│           └── muc/       # Munich
│               ├── tz.yaml
│               └── merkur.yaml
└── schema.yaml            # Configuration schema
```

## Source Configuration

Each source is configured in its own YAML file with the following structure:

```yaml
name: String              # Display name of the source
url: String               # Main URL of the source
language: String          # Primary language (ISO 639-1)
political_orientation: String  # One of: progressive, conservative, liberal, populist, neutral
type: String              # One of: newspaper, magazine, public_service, blog, agency
rescrape_interval: Integer # Optional, override default interval
feed_url: String          # RSS/Atom feed or API endpoint
scrape_method: String     # One of: rss, api, html
topics: Array             # List of covered topics
quality_metrics: Map      # Quality assessment
  fact_checking: String   # One of: low, medium, high
  source_citation: String # One of: low, medium, high
  editorial_standards: String # One of: low, medium, high
coverage: Map             # Geographic coverage
  levels: Array          # Coverage levels from specific to general
  regions: Array         # Specific regions covered
    - level: String      # One of: city, district, state, country, international
      code: String       # Region code (e.g., MUC, BY, DE)
      name: String       # Human-readable region name
```

## Adding New Sources

1. Choose the appropriate directory based on coverage:
   - National sources go in `{country}/national/`
   - Regional sources go in `{country}/regional/{state}/{city}/`

2. Create a new YAML file with the source configuration

3. Follow the schema.yaml for validation rules

## Coverage Levels

Sources can cover multiple geographic levels:
- city: Local city news
- district: City districts or neighborhoods
- state: Regional/state level
- country: National coverage
- international: Global coverage

## Political Orientation

Sources are classified into five categories:
- Progressive: Focus on social change and environmental issues
- Conservative: Emphasis on tradition and gradual change
- Liberal: Market-oriented with focus on individual freedoms
- Populist: Anti-establishment and nationalist
- Neutral: Balanced reporting without strong political positions
