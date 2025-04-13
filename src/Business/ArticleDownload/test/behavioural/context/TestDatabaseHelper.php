<?php

declare(strict_types=1);

namespace Business\ArticleDownload\test\behavioural\context;

use Infrastructure\Persistence\DatabaseHelper;
use PHPUnit\Framework\Assert;

class TestDatabaseHelper
{
    public static function createTables(): void
    {
        DatabaseHelper::createTables();
    }

    public static function dropTables(): void
    {
        DatabaseHelper::dropTables();
    }

    public static function truncateTables(): void
    {
        DatabaseHelper::truncateTables();
    }

    public static function assertTableIsEmpty(string $table): void
    {
        $pdo = DatabaseHelper::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        Assert::assertEquals(0, $stmt->fetchColumn(), "$table table should be empty");
    }
}
