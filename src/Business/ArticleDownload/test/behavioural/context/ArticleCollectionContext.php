<?php

declare(strict_types=1);

namespace Business\ArticleDownload\test\behavioural\context;

use Behat\Behat\Context\Context;
use Business\ArticleDownload\Application\ArticleCollectionService;
use PHPUnit\Framework\Assert;

class ArticleCollectionContext implements Context
{
    private ?\Exception $lastError = null;

    /**
     * @When I update the available articles
     */
    public function iUpdateTheAvailableArticles(): void
    {
        try {
            $service = new ArticleCollectionService();
            $service->updateAvailableArticles();
        } catch (\Exception $e) {
            $this->lastError = $e;
        }
    }

    /**
     * @Then I will receive an error: :errorMessage
     */
    public function iWillReceiveAnError(string $errorMessage): void
    {
        Assert::assertNotNull($this->lastError, 'Expected an error to be thrown');
        Assert::assertEquals($errorMessage, $this->lastError->getMessage());
    }
}
