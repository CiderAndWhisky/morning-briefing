<?php

declare(strict_types=1);

namespace App\Business\ArticleDownload\test\behavioural\context;

use App\Business\ArticleDownload\Domain\Model\NewsSource;
use App\Business\ArticleDownload\Domain\Repository\NewsSourceRepository;
use App\Business\ArticleDownload\Domain\Service\ArticleCollectionService;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class ArticleCollectionContext implements Context
{
    private ArticleCollectionService $collectionService;
    private NewsSourceRepository $repository;
    private ?string $lastError = null;

    public function __construct(
        ArticleCollectionService $collectionService,
        NewsSourceRepository $repository,
    ) {
        $this->collectionService = $collectionService;
        $this->repository = $repository;
    }

    /**
     * @When I update the available articles
     */
    public function iUpdateTheAvailableArticles(): void
    {
        try {
            $this->collectionService->updateAvailableArticles();
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
        }
    }

    /**
     * @Then I will receive an error: :error
     */
    public function iWillReceiveAnError(string $error): void
    {
        Assert::assertEquals($error, $this->lastError);
    }

    /**
     * @Given I have a news source:
     */
    public function iHaveANewsSource(TableNode $table): void
    {
        $data = $table->getHash()[0];
        $source = new NewsSource(
            sourceId: hash('sha256', $data['Name']),
            name: $data['Name'],
            url: $data['RSS'],
            language: 'en',
            type: 'rss',
            scrapeMethod: 'rss',
            contentHash: hash('sha256', $data['Name']),
        );

        $this->repository->save($source);
    }
}

