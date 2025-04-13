<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Entity;

final class NewsSourceEntity implements \JsonSerializable
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
        public readonly ?NewsSourceMetaEntity $meta = null,
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
     *     meta: array<string, mixed>|null
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
}
