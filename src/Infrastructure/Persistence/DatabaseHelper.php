<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

final class DatabaseHelper
{
    private static ?\PDO $pdo = null;

    private static function getEnvVar(string $key, string $default): string
    {
        $value = getenv($key);

        return $value !== false ? $value : $default;
    }

    public static function getConnection(): \PDO
    {
        if (self::$pdo === null) {
            $host = self::getEnvVar('DB_HOST', 'localhost');
            $port = self::getEnvVar('DB_PORT', '5432');
            $dbname = self::getEnvVar('DB_NAME', 'test_db');
            $user = self::getEnvVar('DB_USER', 'postgres');
            $password = self::getEnvVar('DB_PASSWORD', 'postgres');

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            try {
                self::$pdo = new \PDO($dsn, $user, $password);
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                throw new \PDOException('Connection failed: '.$e->getMessage());
            }
        }

        return self::$pdo;
    }

    /**
     * @psalm-suppress UnusedMethod
     * This method is used in tests and database setup scripts
     */
    public static function createTables(): void
    {
        $pdo = self::getConnection();

        $pdo->exec('
            CREATE TABLE IF NOT EXISTS news_sources (
                source_id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                url VARCHAR(255) NOT NULL,
                language VARCHAR(2) NOT NULL,
                political_orientation VARCHAR(50) NOT NULL,
                type VARCHAR(50) NOT NULL,
                feed_url VARCHAR(255) NOT NULL,
                scrape_method VARCHAR(50) NOT NULL,
                topics JSONB NOT NULL,
                quality_metrics JSONB NOT NULL,
                coverage JSONB NOT NULL,
                metadata JSONB NOT NULL,
                content_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL
            )
        ');

        $pdo->exec('
            CREATE TABLE IF NOT EXISTS news_sources_meta (
                source_id VARCHAR(255) PRIMARY KEY REFERENCES news_sources(source_id),
                last_scraped TIMESTAMP WITH TIME ZONE,
                scrape_count INTEGER NOT NULL DEFAULT 0,
                error_count INTEGER NOT NULL DEFAULT 0,
                last_error TEXT,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL
            )
        ');
    }

    /**
     * @psalm-suppress UnusedMethod
     * This method is used in tests and database setup scripts
     */
    public static function dropTables(): void
    {
        $pdo = self::getConnection();
        $pdo->exec('DROP TABLE IF EXISTS news_sources_meta');
        $pdo->exec('DROP TABLE IF EXISTS news_sources');
    }

    /**
     * @psalm-suppress UnusedMethod
     * This method is used in tests and database setup scripts
     */
    public static function truncateTables(): void
    {
        $pdo = self::getConnection();
        $pdo->exec('TRUNCATE TABLE news_sources_meta CASCADE');
        $pdo->exec('TRUNCATE TABLE news_sources CASCADE');
    }
}
