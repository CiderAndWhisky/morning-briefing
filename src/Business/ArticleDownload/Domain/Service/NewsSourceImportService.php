<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\Domain\Service;

use App\Business\ArticleDownload\Domain\Repository\NewsSourceRepository;
use App\Infrastructure\Config\FileConfigService;
use App\Infrastructure\Persistence\Dto\NewsSourceDto;
use App\Infrastructure\Persistence\Dto\NewsSourceMetaDto;
use Symfony\Component\Yaml\Yaml;

class NewsSourceImportService
{
    public function __construct(
        private readonly NewsSourceRepository $repository,
        private readonly FileConfigService $configService,
    ) {
    }

    public function importNewsSources(): void
    {
        $files = $this->configService->listFiles('news_sources', '*.yaml');

        foreach ($files as $file) {
            $content = $this->configService->readConfigFile('news_sources/'.$file);
            $data = Yaml::parse($content);

            if (!is_array($data)) {
                throw new \RuntimeException('Invalid YAML format in file: '.$file);
            }

            $sourceId = hash('sha256', $file);
            $contentHash = hash('sha256', $content);

            $existingSource = $this->repository->findById($sourceId);

            if ($existingSource !== null && $existingSource->contentHash === $contentHash) {
                continue;
            }

            $now = new \DateTimeImmutable();
            $createdAt = $existingSource?->createdAt ?? $now;

            $meta = $existingSource?->meta ?? new NewsSourceMetaDto(
                sourceId: $sourceId,
                lastScraped: null,
                scrapeCount: 0,
                errorCount: 0,
                lastError: null,
                createdAt: $now,
                updatedAt: $now,
            );

            $newsSourceDto = new NewsSourceDto(
                sourceId: $sourceId,
                name: $this->getStringValue($data, 'name'),
                url: $this->getStringValue($data, 'url'),
                language: $this->getStringValue($data, 'language'),
                politicalOrientation: $this->getStringValue($data, 'political_orientation'),
                type: $this->getStringValue($data, 'type'),
                feedUrl: $this->getStringValue($data, 'feed_url'),
                scrapeMethod: $this->getStringValue($data, 'scrape_method'),
                topics: $this->getArrayValue($data, 'topics'),
                qualityMetrics: $this->getFloatArrayValue($data, 'quality_metrics'),
                coverage: $this->getBoolArrayValue($data, 'coverage'),
                metadata: $this->getArrayValue($data, 'metadata'),
                contentHash: $contentHash,
                createdAt: $createdAt,
                updatedAt: $now,
                meta: $meta,
            );

            $this->repository->save($newsSourceDto);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getStringValue(array $data, string $key): string
    {
        if (!isset($data[$key]) || !is_string($data[$key])) {
            throw new \RuntimeException("Missing or invalid string value for key: $key");
        }

        return $data[$key];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    private function getArrayValue(array $data, string $key): array
    {
        if (!isset($data[$key]) || !is_array($data[$key])) {
            throw new \RuntimeException("Missing or invalid array value for key: $key");
        }

        return $data[$key];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, float>
     */
    private function getFloatArrayValue(array $data, string $key): array
    {
        if (!isset($data[$key]) || !is_array($data[$key])) {
            throw new \RuntimeException("Missing or invalid array value for key: $key");
        }

        $result = [];
        foreach ($data[$key] as $k => $v) {
            if (!is_string($k) || !is_float($v)) {
                throw new \RuntimeException("Invalid float array value for key: $key");
            }
            $result[$k] = $v;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, bool>
     */
    private function getBoolArrayValue(array $data, string $key): array
    {
        if (!isset($data[$key]) || !is_array($data[$key])) {
            throw new \RuntimeException("Missing or invalid array value for key: $key");
        }

        $result = [];
        foreach ($data[$key] as $k => $v) {
            if (!is_string($k) || !is_bool($v)) {
                throw new \RuntimeException("Invalid bool array value for key: $key");
            }
            $result[$k] = $v;
        }

        return $result;
    }
}
