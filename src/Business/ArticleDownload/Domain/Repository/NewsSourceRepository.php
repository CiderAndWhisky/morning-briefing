<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\Domain\Repository;

use App\Infrastructure\Persistence\Dto\NewsSourceDto;

interface NewsSourceRepository
{
    /**
     * Saves or updates a news source.
     * If a news source with the same source_id exists, it will be updated.
     * Otherwise, a new news source will be created.
     */
    public function save(NewsSourceDto $newsSource): void;

    /**
     * Finds a news source by its source ID.
     * Returns null if no news source is found.
     */
    public function findById(string $sourceId): ?NewsSourceDto;
}
