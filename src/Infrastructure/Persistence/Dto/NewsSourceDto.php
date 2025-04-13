<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Dto;

final class NewsSourceDto
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
        public readonly ?NewsSourceMetaDto $meta = null,
    ) {
    }
}
