<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\test\behavioural\context;

use PDO;

class TestDatabaseHelper
{
    public function __construct(
        private readonly PDO $pdo,
    ) {
    }

    public function truncateTables(): void
    {
        $this->pdo->exec('TRUNCATE TABLE news_sources_meta CASCADE');
        $this->pdo->exec('TRUNCATE TABLE news_sources CASCADE');
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
