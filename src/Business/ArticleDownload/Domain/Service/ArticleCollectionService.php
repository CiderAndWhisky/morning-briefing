<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\Domain\Service;

use App\Business\ArticleDownload\Domain\Repository\NewsSourceRepository;

class ArticleCollectionService
{
    public function __construct(
        private readonly NewsSourceRepository $repository,
    ) {
    }

    public function updateAvailableArticles(): void
    {
        $sources = $this->repository->findAll();
        if (empty($sources)) {
            throw new \RuntimeException('Configuration not found');
        }

        // TODO: Implement article collection logic
    }
}