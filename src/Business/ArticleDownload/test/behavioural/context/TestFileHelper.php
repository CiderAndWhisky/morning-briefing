<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\test\behavioural\context;

use App\Infrastructure\Config\FileConfigService;

class TestFileHelper
{
    public function __construct(
        private readonly FileConfigService $configService,
    ) {
    }

    public function setupTestFiles(): void
    {
        $this->configService->removeDirectory('news_sources');
        $this->configService->ensureDirectoryExists('news_sources');
    }

    public function cleanupTestFiles(): void
    {
        $this->configService->removeDirectory('news_sources');
    }

    public function writeTestFile(string $path, string $content): void
    {
        $this->configService->writeConfigFile('news_sources/' . $path, $content);
    }

    public function readTestFile(string $path): string
    {
        return $this->configService->readConfigFile('news_sources/' . $path);
    }
}
