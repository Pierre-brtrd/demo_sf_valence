<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends WebTestCase
{
    protected $client;

    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/Fixtures/TagFixtures.yaml',
            dirname(__DIR__) . '/Fixtures/ArticleFixtures.yaml',
        ]);
    }

    public function testGetHomePage()
    {
        $this->client->request('GET', '');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        /* OU */
        // $this->assertResponseIsSuccessful();
    }

    public function testHeading1Homepage()
    {
        $this->client->request('GET', '');
        $this->assertSelectorTextContains('h1.title', 'Bienvenue sur l\'application Symfony 6');
    }

    public function testNavbarHomepage()
    {
        $this->client->request('GET', '');

        $this->assertSelectorExists('header');
    }

    // public function testFooterHomepage()
    // {
    //     $this->client->request('GET', '');

    //     $this->assertSelectorExists('footer');
    // }

    public function testArticlesNumberHomePage()
    {
        $crawler = $this->client->request('GET', '');

        $this->assertCount(6, $crawler->filter('.blog-card'));
    }
}
