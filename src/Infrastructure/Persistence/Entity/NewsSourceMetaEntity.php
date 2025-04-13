<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Entity;

final class NewsSourceMetaEntity implements \JsonSerializable
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

    /**
     * @return array{
     *     sourceId: string,
     *     lastScraped: string|null,
     *     scrapeCount: int,
     *     errorCount: int,
     *     lastError: string|null,
     *     createdAt: string,
     *     updatedAt: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'sourceId' => $this->sourceId,
            'lastScraped' => $this->lastScraped?->format('c'),
            'scrapeCount' => $this->scrapeCount,
            'errorCount' => $this->errorCount,
            'lastError' => $this->lastError,
            'createdAt' => $this->createdAt->format('c'),
            'updatedAt' => $this->updatedAt->format('c'),
        ];
    }
}
