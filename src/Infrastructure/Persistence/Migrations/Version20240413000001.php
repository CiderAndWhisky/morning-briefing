<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240413000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create news_sources and news_sources_meta tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE news_sources (
            source_id VARCHAR(64) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            language VARCHAR(2) NOT NULL,
            political_orientation VARCHAR(50),
            type VARCHAR(50) NOT NULL,
            feed_url VARCHAR(255),
            scrape_method VARCHAR(50) NOT NULL,
            topics JSONB,
            quality_metrics JSONB,
            coverage JSONB,
            metadata JSONB,
            content_hash VARCHAR(64) NOT NULL,
            created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )');

        $this->addSql('CREATE TABLE news_sources_meta (
            source_id VARCHAR(64) PRIMARY KEY REFERENCES news_sources(source_id),
            last_scraped TIMESTAMP WITH TIME ZONE,
            last_successful_scrape TIMESTAMP WITH TIME ZONE,
            scrape_attempts INTEGER DEFAULT 0,
            scrape_errors INTEGER DEFAULT 0,
            last_error TEXT,
            created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE news_sources_meta');
        $this->addSql('DROP TABLE news_sources');
    }
}