<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Business\ArticleDownload\Domain\Repository\NewsSourceRepository;
use App\Infrastructure\Persistence\DatabaseHelper;
use App\Infrastructure\Persistence\Dto\NewsSourceDto;
use App\Infrastructure\Persistence\Dto\NewsSourceMetaDto;
use App\Infrastructure\Persistence\Entity\NewsSourceEntity;
use App\Infrastructure\Persistence\Entity\NewsSourceMetaEntity;

/**
 * @psalm-suppress UnusedClass
 * This class is used as a concrete implementation of NewsSourceRepository
 */
final class PostgresNewsSourceRepository implements NewsSourceRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseHelper::getConnection();
    }

    public function save(NewsSourceDto $newsSource): void
    {
        $entity = new NewsSourceEntity(
            $newsSource->sourceId,
            $newsSource->name,
            $newsSource->url,
            $newsSource->language,
            $newsSource->politicalOrientation,
            $newsSource->type,
            $newsSource->feedUrl,
            $newsSource->scrapeMethod,
            $newsSource->topics,
            $newsSource->qualityMetrics,
            $newsSource->coverage,
            $newsSource->metadata,
            $newsSource->contentHash,
            $newsSource->createdAt,
            $newsSource->updatedAt,
            $newsSource->meta !== null ? new NewsSourceMetaEntity(
                $newsSource->meta->sourceId,
                $newsSource->meta->lastScraped,
                $newsSource->meta->scrapeCount,
                $newsSource->meta->errorCount,
                $newsSource->meta->lastError,
                $newsSource->meta->createdAt,
                $newsSource->meta->updatedAt,
            ) : null,
        );

        $stmt = $this->pdo->prepare('
            INSERT INTO news_sources (
                source_id, name, url, language, political_orientation, type, feed_url,
                scrape_method, topics, quality_metrics, coverage, metadata, content_hash,
                created_at, updated_at
            ) VALUES (
                :source_id, :name, :url, :language, :political_orientation, :type, :feed_url,
                :scrape_method, :topics, :quality_metrics, :coverage, :metadata, :content_hash,
                :created_at, :updated_at
            ) ON CONFLICT (source_id) DO UPDATE SET
                name = :name,
                url = :url,
                language = :language,
                political_orientation = :political_orientation,
                type = :type,
                feed_url = :feed_url,
                scrape_method = :scrape_method,
                topics = :topics,
                quality_metrics = :quality_metrics,
                coverage = :coverage,
                metadata = :metadata,
                content_hash = :content_hash,
                updated_at = :updated_at
        ');

        $stmt->execute([
            'source_id' => $entity->sourceId,
            'name' => $entity->name,
            'url' => $entity->url,
            'language' => $entity->language,
            'political_orientation' => $entity->politicalOrientation,
            'type' => $entity->type,
            'feed_url' => $entity->feedUrl,
            'scrape_method' => $entity->scrapeMethod,
            'topics' => json_encode($entity->topics),
            'quality_metrics' => json_encode($entity->qualityMetrics),
            'coverage' => json_encode($entity->coverage),
            'metadata' => json_encode($entity->metadata),
            'content_hash' => $entity->contentHash,
            'created_at' => $entity->createdAt->format('c'),
            'updated_at' => $entity->updatedAt->format('c'),
        ]);

        if ($entity->meta !== null) {
            $this->saveMeta($entity->meta);
        }
    }

    private function saveMeta(NewsSourceMetaEntity $meta): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO news_sources_meta (
                source_id, last_scraped, scrape_count, error_count, last_error,
                created_at, updated_at
            ) VALUES (
                :source_id, :last_scraped, :scrape_count, :error_count, :last_error,
                :created_at, :updated_at
            ) ON CONFLICT (source_id) DO UPDATE SET
                last_scraped = :last_scraped,
                scrape_count = :scrape_count,
                error_count = :error_count,
                last_error = :last_error,
                updated_at = :updated_at
        ');

        $stmt->execute([
            'source_id' => $meta->sourceId,
            'last_scraped' => $meta->lastScraped?->format('c'),
            'scrape_count' => $meta->scrapeCount,
            'error_count' => $meta->errorCount,
            'last_error' => $meta->lastError,
            'created_at' => $meta->createdAt->format('c'),
            'updated_at' => $meta->updatedAt->format('c'),
        ]);
    }

    public function findById(string $sourceId): ?NewsSourceDto
    {
        $stmt = $this->pdo->prepare('
            SELECT ns.*, nsm.*
            FROM news_sources ns
            LEFT JOIN news_sources_meta nsm ON ns.source_id = nsm.source_id
            WHERE ns.source_id = :source_id
        ');
        $stmt->execute(['source_id' => $sourceId]);
        /** @var array{
         *     source_id: string,
         *     name: string,
         *     url: string,
         *     language: string,
         *     political_orientation: string,
         *     type: string,
         *     feed_url: string,
         *     scrape_method: string,
         *     topics: string,
         *     quality_metrics: string,
         *     coverage: string,
         *     metadata: string,
         *     content_hash: string,
         *     created_at: string,
         *     updated_at: string,
         *     last_scraped: ?string,
         *     scrape_count: string,
         *     error_count: string,
         *     last_error: ?string
         * }|false $row */
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        /** @var array<string> $topics */
        $topics = json_decode($row['topics'], true);
        /** @var array<string, float> $qualityMetrics */
        $qualityMetrics = json_decode($row['quality_metrics'], true);
        /** @var array<string, bool> $coverage */
        $coverage = json_decode($row['coverage'], true);
        /** @var array<string, mixed> $metadata */
        $metadata = json_decode($row['metadata'], true);

        $meta = null;
        if ($row['last_scraped'] !== null) {
            $meta = new NewsSourceMetaDto(
                $row['source_id'],
                new \DateTimeImmutable($row['last_scraped']),
                (int) $row['scrape_count'],
                (int) $row['error_count'],
                $row['last_error'],
                new \DateTimeImmutable($row['created_at']),
                new \DateTimeImmutable($row['updated_at']),
            );
        }

        return new NewsSourceDto(
            $row['source_id'],
            $row['name'],
            $row['url'],
            $row['language'],
            $row['political_orientation'],
            $row['type'],
            $row['feed_url'],
            $row['scrape_method'],
            $topics,
            $qualityMetrics,
            $coverage,
            $metadata,
            $row['content_hash'],
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at']),
            $meta,
        );
    }
}
