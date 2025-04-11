# Morning Briefing Implementation Concept

## Overview
The Morning Briefing will gather, filter, and summarize news articles from multiple sources in configurable languages, creating a morning briefing focused on the most important news.

## Implementation Steps

### 1. News Source Identification
- Identify reliable news sources in configurable languages
- Focus on sources that are easily scrapeable
- Document sources and their characteristics (update frequency, structure, etc.)
- Create a configuration system for managing sources
#### Technical implementation
- Base config: YAML files with news sources and meta data like language, political orientation (conservative, progressive, populist, ...). One YAML file per region (country or smaller for regional news), re-scrape interval, scrape method

### 2. Article List Retrieval and Topic Filtering
#### Functional Requirements
- Create a module to fetch article lists (title, topic, link)
- Implement configurable topic filtering
- Define configurable uninteresting topics
- Make filtering rules configurable and extensible

#### Technical Implementation
- User config: YAML files that define which sources to use and filtering rules. One YAML file per user, independent from base config
- Cache: Store article lists with timestamps, update only when re-scrape interval is reached
- Filter: Apply user-defined topic filters to article lists

### 3. Article Download and Storage
#### Functional Requirements
- Download full content of filtered articles
- Store articles in a structured format
- Implement caching to avoid repeated downloads
- Handle different article formats and structures

#### Technical Implementation
- Store articles in PostgreSQL with the following metadata:
  - id: unique identifier
  - source_id: reference to source
  - url: original article URL
  - title: article title
  - content: full article content
  - language: article language
  - published_at: original publication date
  - scraped_at: when we downloaded it
  - topics: array of topics
  - summary: optional summary
  - vector: optional vector representation
  - status: new|processed|archived
- Cache: Store raw HTML with timestamps to avoid re-downloading unchanged articles
- Process: Extract and clean content using source-specific rules from base config

### 4. Article Vectorization
#### Functional Requirements
- Implement text vectorization for articles
- Handle multiple languages
- Consider language-specific vectorization approaches
- Store vectors efficiently

#### Technical Implementation
- Use OpenAI embeddings for vectorization
- Store vectors in PostgreSQL using pgvector extension
- Cache vectors to avoid re-computation
- Process: Vectorize content using language-specific models

### 5. Article Grouping
#### Functional Requirements
- Group articles based on vector similarity
- Implement clustering algorithms
- Handle multilingual content in grouping
- Define similarity thresholds

#### Technical Implementation
- Use HDBSCAN for clustering articles based on vector similarity
- Store groups in PostgreSQL with the following structure:
  - groups table:
    - id: unique identifier
    - created_at: timestamp
    - cluster_id: HDBSCAN cluster identifier
    - size: number of articles in group
    - centroid: vector representation of group center
  - group_articles table:
    - group_id: reference to group
    - article_id: reference to article
    - similarity_score: similarity to group centroid
- Process: Group articles using similarity thresholds from user config
- Cache: Store group assignments to avoid re-computation

### 6. Importance Filtering
#### Functional Requirements
- Implement importance scoring system
- Consider factors:
  - Number of sources reporting
  - Order in news outlets
  - User preferences
  - Historical importance
- Filter to top N most important articles

#### Technical Implementation
- Store importance scores in PostgreSQL:
  - article_importance table:
    - article_id: reference to article
    - score: computed importance score
    - factors: JSON with individual factor scores
    - computed_at: timestamp
- Process: Compute scores using weighted factors from user config
- Cache: Store scores to avoid re-computation

### 7. Article Summarization
#### Functional Requirements
- Create summaries for filtered articles
- Include links to full articles
- Handle multilingual content
- Maintain context and relationships

#### Technical Implementation
- Use OpenAI for article summarization
- Store summaries in PostgreSQL:
  - article_summaries table:
    - article_id: reference to article
    - summary: generated summary
    - language: summary language
    - created_at: timestamp
    - model: used model version
- Process: Generate summaries using language-specific prompts from user config
- Cache: Store summaries to avoid re-computation

### 8. Briefing Creation
#### Functional Requirements
- Merge summaries into a coherent briefing
- Implement visual formatting
- Include source attribution
- Add navigation and structure

#### Technical Implementation
- Store briefings in PostgreSQL:
  - briefings table:
    - id: unique identifier
    - created_at: timestamp
    - user_id: reference to user
    - content: JSON with structured briefing
    - format: briefing format (html, markdown, etc.)
- Process: Generate briefings using templates from user config
- Cache: Store generated briefings for quick access

## Technical Considerations

### AI Model Configuration
- Support both local and cloud-based models:
  - Local models (e.g., Deepseek, Llama) for cost efficiency
  - Cloud models (e.g., OpenAI) for higher quality
- Configuration options:
  ```yaml
  ai:
    vectorization:
      provider: local|openai
      model: deepseek-coder|text-embedding-ada-002
      local_path: /path/to/model
    summarization:
      provider: local|openai
      model: llama2|gpt-4
      local_path: /path/to/model
  ```
- Benefits of local processing:
  - Lower costs
  - No API rate limits
  - Better privacy
  - Faster processing for small batches
- Benefits of cloud processing:
  - Higher quality results
  - No local resource usage
  - Automatic updates
  - Better multilingual support

### Performance Considerations
- M3Pro processor should handle:
  - Local model inference
  - Vector operations
  - Database operations
- Batch processing for efficiency
- Caching to minimize recomputation
- Parallel processing where possible
