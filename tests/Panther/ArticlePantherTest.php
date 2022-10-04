<?php

namespace App\Tests\Panther;

use Facebook\WebDriver\WebDriverBy;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Panther\PantherTestCase;

class ArticlePantherTest extends PantherTestCase
{
    protected $client;

    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = self::createPantherClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/Fixtures/TagFixtures.yaml',
            dirname(__DIR__) . '/Fixtures/ArticleFixtures.yaml',
        ]);
    }

    public function testArticleNumberPage()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->assertCount(6, $crawler->filter('.blog-list .blog-card'));
    }

    public function testArticleBtnShowMore()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.btn-show-more');

        $this->client->executeScript("document.querySelector('.btn-show-more').click()");

        $this->client->waitForEnabled('.btn-show-more', 3);

        $crawler = $this->client->refreshCrawler();

        $this->assertCount(12, $crawler->filter('.blog-list .blog-card'));
    }

    public function testLastPageBtnShowMore()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.btn-show-more');

        foreach (range(1, 3) as $i) {
            $this->client->executeScript("document.querySelector('.btn-show-more').click()");

            $this->client->waitForEnabled('.btn-show-more', 3);
        }

        $this->assertSelectorIsNotVisible('.btn-show-more');
    }

    public function testFormSearchTextFilter()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.form-filter', 3);

        $search = $this->client->findElement(WebDriverBy::cssSelector('.form-filter input[type="text"]'));
        $search->sendKeys('Article de test');

        $this->client->waitFor('.content-response', 4);

        // Wait for the animation Flipper
        sleep(1.5);

        $crawler = $this->client->refreshCrawler();

        $this->assertCount(1, $crawler->filter('.blog-list .blog-card'));
    }

    public function testFormSearchTextNotResultFilter()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.form-filter', 3);

        $search = $this->client->findElement(WebDriverBy::cssSelector('.form-filter input[type="text"]'));
        $search->sendKeys('Article de testkqjsdhfgkjsdhfiuhsdhs');

        $this->client->waitFor('.content-response', 4);

        // Wait for the animation Flipper
        sleep(1.5);

        $this->assertSelectorExists('#article-no-response');
    }

    public function testFormTagFilter()
    {
        $crawler = $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.form-filter', 3);

        $this->client->findElement(WebDriverBy::cssSelector('.form-filter input[type="checkbox"]'))->click();

        $this->client->waitFor('.content-response', 4);

        // Wait for the animation Flipper
        sleep(1.5);

        $crawler = $this->client->refreshCrawler();

        $this->assertCount(2, $crawler->filter('.blog-list .blog-card'));
    }

    public function testSortableBtnFilter()
    {
        $this->client->request('GET', '/article/liste');

        $this->client->waitFor('.sortable[title="Nom "]', 2);

        $this->client->findElement(WebDriverBy::cssSelector('.sortable[title="Nom "]'))->click();

        $this->client->waitFor('.content-response', 4);

        // Wait for the animation flipper complete
        sleep(1.5);

        $this->assertSelectorTextContains('.blog-list .blog-card .blog-card-content .blog-card-header', 'Article 1');
    }
}
