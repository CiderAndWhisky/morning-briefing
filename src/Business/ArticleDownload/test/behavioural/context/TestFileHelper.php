<?php

declare(strict_types=1);

namespace Business\ArticleDownload\test\behavioural\context;

class TestFileHelper
{
    private const CACHE_DIR = __DIR__.'/cache';

    public static function ensureTestConfigDir(): void
    {
        if (!file_exists(self::CACHE_DIR)) {
            mkdir(self::CACHE_DIR, 0777, true);
        }
    }

    public static function createTestYaml(string $path, string $content): void
    {
        $fullPath = self::CACHE_DIR.'/'.$path;
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($fullPath, $content);
    }

    public static function cleanupTestFiles(): void
    {
        if (file_exists(self::CACHE_DIR)) {
            self::removeDirectory(self::CACHE_DIR);
        }
    }

    private static function removeDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir.'/'.$file;
            is_dir($path) ? self::removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    public static function getTestConfigDir(): string
    {
        return self::CACHE_DIR;
    }
}
