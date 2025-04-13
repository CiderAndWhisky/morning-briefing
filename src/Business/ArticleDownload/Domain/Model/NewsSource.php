<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\Domain\Model;

use App\Infrastructure\Persistence\Entity\NewsSourceEntity;

final class NewsSource implements \JsonSerializable
{
    public function __construct(
        public readonly string $sourceId,
        public readonly string $name,
        public readonly string $url,
        public readonly string $language,
        public readonly string $politicalOrientation,
        public readonly string $type,
        public readonly string $feedUrl,
        public readonly string $scrapeMethod,
        /** @var array<string> */
        public readonly array $topics,
        /** @var array<string, float> */
        public readonly array $qualityMetrics,
        /** @var array<string, bool> */
        public readonly array $coverage,
        /** @var array<string, mixed> */
        public readonly array $metadata,
        public readonly string $contentHash,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
        public readonly ?NewsSourceMeta $meta = null,
    ) {
    }

    /**
     * @return array{
     *     sourceId: string,
     *     name: string,
     *     url: string,
     *     language: string,
     *     politicalOrientation: string,
     *     type: string,
     *     feedUrl: string,
     *     scrapeMethod: string,
     *     topics: array<string>,
     *     qualityMetrics: array<string, float>,
     *     coverage: array<string, bool>,
     *     metadata: array<string, mixed>,
     *     contentHash: string,
     *     createdAt: string,
     *     updatedAt: string,
     *     meta: ?array<string, mixed>
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'sourceId' => $this->sourceId,
            'name' => $this->name,
            'url' => $this->url,
            'language' => $this->language,
            'politicalOrientation' => $this->politicalOrientation,
            'type' => $this->type,
            'feedUrl' => $this->feedUrl,
            'scrapeMethod' => $this->scrapeMethod,
            'topics' => $this->topics,
            'qualityMetrics' => $this->qualityMetrics,
            'coverage' => $this->coverage,
            'metadata' => $this->metadata,
            'contentHash' => $this->contentHash,
            'createdAt' => $this->createdAt->format('c'),
            'updatedAt' => $this->updatedAt->format('c'),
            'meta' => $this->meta?->jsonSerialize(),
        ];
    }

    public static function fromEntity(NewsSourceEntity $entity): self
    {
        $meta = null;
        if ($entity->meta !== null) {
            $meta = NewsSourceMeta::fromEntity($entity->meta);
        }

        return new self(
            $entity->sourceId,
            $entity->name,
            $entity->url,
            $entity->language,
            $entity->politicalOrientation,
            $entity->type,
            $entity->feedUrl,
            $entity->scrapeMethod,
            $entity->topics,
            $entity->qualityMetrics,
            $entity->coverage,
            $entity->metadata,
            $entity->contentHash,
            $entity->createdAt,
            $entity->updatedAt,
            $meta,
        );
    }

    public function toEntity(): NewsSourceEntity
    {
        $meta = null;
        if ($this->meta !== null) {
            $meta = $this->meta->toEntity();
        }

        return new NewsSourceEntity(
            $this->sourceId,
            $this->name,
            $this->url,
            $this->language,
            $this->politicalOrientation,
            $this->type,
            $this->feedUrl,
            $this->scrapeMethod,
            $this->topics,
            $this->qualityMetrics,
            $this->coverage,
            $this->metadata,
            $this->contentHash,
            $this->createdAt,
            $this->updatedAt,
            $meta,
        );
    }
}
