<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\Domain\Model;

use App\Infrastructure\Persistence\Entity\NewsSourceMetaEntity;

final class NewsSourceMeta implements \JsonSerializable
{
    public function __construct(
        public readonly string $sourceId,
        public readonly \DateTimeImmutable $lastScraped,
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
     *     lastScraped: string,
     *     scrapeCount: int,
     *     errorCount: int,
     *     lastError: ?string,
     *     createdAt: string,
     *     updatedAt: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'sourceId' => $this->sourceId,
            'lastScraped' => $this->lastScraped->format('c'),
            'scrapeCount' => $this->scrapeCount,
            'errorCount' => $this->errorCount,
            'lastError' => $this->lastError,
            'createdAt' => $this->createdAt->format('c'),
            'updatedAt' => $this->updatedAt->format('c'),
        ];
    }

    public static function fromEntity(NewsSourceMetaEntity $entity): self
    {
        return new self(
            $entity->sourceId,
            $entity->lastScraped ?? new \DateTimeImmutable(),
            $entity->scrapeCount,
            $entity->errorCount,
            $entity->lastError,
            $entity->createdAt,
            $entity->updatedAt,
        );
    }

    public function toEntity(): NewsSourceMetaEntity
    {
        return new NewsSourceMetaEntity(
            $this->sourceId,
            $this->lastScraped,
            $this->scrapeCount,
            $this->errorCount,
            $this->lastError,
            $this->createdAt,
            $this->updatedAt,
        );
    }
}
