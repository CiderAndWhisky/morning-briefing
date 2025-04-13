<?php

declare(strict_types=1);

namespace Business\ArticleDownload\test\behavioural\context;

use Behat\Behat\Context\Context;
use Business\ArticleDownload\Domain\Model\NewsSource;
use Business\ArticleDownload\Domain\Repository\NewsSourceRepository;
use Business\ArticleDownload\Domain\Service\NewsSourceImportService;
use Infrastructure\Config\FileConfigService;
use PHPUnit\Framework\Assert;

class NewsSourceImportContext implements Context
{
    private NewsSourceImportService $importService;
    private NewsSourceRepository $repository;
    private FileConfigService $configService;
    private ?NewsSource $lastImportedSource = null;

    public function __construct(
        NewsSourceImportService $importService,
        NewsSourceRepository $repository,
        FileConfigService $configService,
    ) {
        $this->importService = $importService;
        $this->repository = $repository;
        $this->configService = $configService;
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario(): void
    {
        $this->configService->removeDirectory('news_sources');
        $this->configService->ensureDirectoryExists('news_sources');
    }

    /**
     * @AfterScenario
     */
    public function afterScenario(): void
    {
        $this->configService->removeDirectory('news_sources');
    }

    /**
     * @Given I have news source YAML files in the configuration directory
     */
    public function iHaveNewsSourceYamlFilesInTheConfigurationDirectory(): void
    {
        // This is a setup step, no implementation needed
    }

    /**
     * @Given I have an empty news_sources table
     */
    public function iHaveAnEmptyNewsSourcesTable(): void
    {
        $pdo = $this->repository->getPdo();
        $pdo->exec('TRUNCATE TABLE news_sources CASCADE');
    }

    /**
     * @Given I have an empty news_sources_meta table
     */
    public function iHaveAnEmptyNewsSourcesMetaTable(): void
    {
        $pdo = $this->repository->getPdo();
        $pdo->exec('TRUNCATE TABLE news_sources_meta CASCADE');
    }

    /**
     * @Given I have a news source YAML file at :path
     */
    public function iHaveANewsSourceYamlFileAt(string $path): void
    {
        $content = <<<YAML
name: "El Mundo"
url: "https://www.elmundo.es"
language: "es"
political_orientation: "center-right"
type: "newspaper"
feed_url: "https://e00-elmundo.uecdn.es/elmundo/rss/portada.xml"
scrape_method: "rss"
topics:
  - "politics"
  - "economy"
  - "sports"
quality_metrics:
  reliability: 0.8
  fact_checking: 0.7
coverage:
  national: true
  international: true
metadata:
  publisher: "Unidad Editorial"
  founded: 1989
YAML;

        $this->configService->writeConfigFile('news_sources/'.$path, $content);
    }

    /**
     * @When I import news sources
     */
    public function iImportNewsSources(): void
    {
        $this->importService->importNewsSources();
    }

    /**
     * @Then the news source should be imported into the news_sources table
     */
    public function theNewsSourceShouldBeImportedIntoTheNewsSourcesTable(): void
    {
        $sources = $this->repository->findAll();
        Assert::assertCount(1, $sources);

        $this->lastImportedSource = $sources[0];
        Assert::assertEquals('El Mundo', $this->lastImportedSource->name);
        Assert::assertEquals('https://www.elmundo.es', $this->lastImportedSource->url);
        Assert::assertEquals('es', $this->lastImportedSource->language);
    }

    /**
     * @Then the news source meta should be created in the news_sources_meta table
     */
    public function theNewsSourceMetaShouldBeCreatedInTheNewsSourcesMetaTable(): void
    {
        Assert::assertNotNull($this->lastImportedSource);
        Assert::assertNotNull($this->lastImportedSource->meta);

        $meta = $this->lastImportedSource->meta;
        Assert::assertNull($meta->lastScraped);
        Assert::assertNull($meta->lastSuccessfulScrape);
        Assert::assertEquals(0, $meta->scrapeAttempts);
        Assert::assertEquals(0, $meta->scrapeErrors);
        Assert::assertNull($meta->lastError);
    }

    /**
     * @Then the source ID should be a hash of :path
     */
    public function theSourceIdShouldBeAHashOf(string $path): void
    {
        Assert::assertNotNull($this->lastImportedSource);
        $expectedHash = hash('sha256', $path);
        Assert::assertEquals($expectedHash, $this->lastImportedSource->sourceId);
    }

    /**
     * @Then the content hash should match the YAML file content
     */
    public function theContentHashShouldMatchTheYamlFileContent(): void
    {
        Assert::assertNotNull($this->lastImportedSource);
        $path = 'es/national/elmundo.yaml';
        $content = $this->configService->readConfigFile('news_sources/'.$path);
        $expectedHash = hash('sha256', $content);
        Assert::assertEquals($expectedHash, $this->lastImportedSource->contentHash);
    }
}
