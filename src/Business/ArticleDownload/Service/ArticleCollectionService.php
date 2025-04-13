<?php

declare(strict_types=1);

namespace Business\ArticleDownload\Application;

/**
 * @psalm-suppress UnusedClass
 * This class is used in the application layer for article collection
 */
class ArticleCollectionService
{
    public function updateAvailableArticles(): void
    {
        throw new \RuntimeException('Configuration not found');
    }
}
