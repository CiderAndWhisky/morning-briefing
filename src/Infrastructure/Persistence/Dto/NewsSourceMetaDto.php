<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Dto;

final class NewsSourceMetaDto
{
    public function __construct(
        public readonly string $sourceId,
        public readonly ?\DateTimeImmutable $lastScraped,
        public readonly int $scrapeCount,
        public readonly int $errorCount,
        public readonly ?string $lastError,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
    ) {
    }
}
