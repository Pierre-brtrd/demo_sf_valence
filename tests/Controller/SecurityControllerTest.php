<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    protected $client;

    protected $databaseTool;

    protected $repoUser;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->repoUser = self::getContainer()->get(UserRepository::class);

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserFixtures.yaml',
        ]);
    }

    public function testGetLoginPage()
    {
        $this->client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testHeading1LoginPage()
    {
        $this->client->request('GET', '/login');

        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testAdminArticleNotLoggedIn()
    {
        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testAdminUserNotLoggedIn()
    {
        $this->client->request('GET', '/admin/user');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testAdminArticleBadUserLoggedIn()
    {
        $user = $this->repoUser->find(3);

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminUserBadUserLoggedIn()
    {
        $user = $this->repoUser->find(3);

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/user');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminArticleGoodUserLoggedIn()
    {
        $user = $this->repoUser->findOneByEmail('admin@example.com');

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdminUserGoodUserLoggedIn()
    {
        $user = $this->repoUser->findOneByEmail('admin@example.com');

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/user');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetRegisterPage()
    {
        $this->client->request('GET', '/register');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testHeading1RegisterPage()
    {
        $this->client->request('GET', '/register');

        $this->assertSelectorTextContains('h1', 'inscrire');
    }

    public function testRegisterNewUser()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[prenom]' => 'John',
            'registration_form[nom]' => 'Doe',
            'registration_form[username]' => 'John Doe',
            'registration_form[age]' => 45,
            'registration_form[email]' => 'john@doe.com',
            'registration_form[password][first]' => 'Test1234',
            'registration_form[password][second]' => 'Test1234',
            'registration_form[address]' => 'XX rue de paradis',
            'registration_form[ville]' => 'Paris',
            'registration_form[zipCode]' => '75001'
        ]);

        $this->client->submit($form);

        $newUser = $this->repoUser->findOneByEmail('john@doe.com');

        if (!$newUser) {
            throw new Exception('User not created.');
        }

        $this->assertResponseRedirects();
    }

    public function testRegisterNewUserWithInvalidEmail()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[prenom]' => 'John',
            'registration_form[nom]' => 'Doe',
            'registration_form[username]' => 'John Doe',
            'registration_form[age]' => 45,
            'registration_form[email]' => 'john@d',
            'registration_form[password][first]' => 'Test1234',
            'registration_form[password][second]' => 'Test1234',
            'registration_form[address]' => 'XX rue de paradis',
            'registration_form[ville]' => 'Paris',
            'registration_form[zipCode]' => '75001'
        ]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('div.invalid-feedback', 'Veuillez rentrer un email valide.');
    }

    public function testRegisterNewUserWithInvalidZipCode()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[prenom]' => 'John',
            'registration_form[nom]' => 'Doe',
            'registration_form[username]' => 'John Doe',
            'registration_form[age]' => 45,
            'registration_form[email]' => 'john@doe.com',
            'registration_form[password][first]' => 'Test1234',
            'registration_form[password][second]' => 'Test1234',
            'registration_form[address]' => 'XX rue de paradis',
            'registration_form[ville]' => 'Paris',
            'registration_form[zipCode]' => 'kjhsdkfjh'
        ]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('div.invalid-feedback', 'Veuillez rentrer un code postal valide.');
    }

    public function testRegisterNewUserWithInvalidPassword()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[prenom]' => 'John',
            'registration_form[nom]' => 'Doe',
            'registration_form[username]' => 'John Doe',
            'registration_form[age]' => 45,
            'registration_form[email]' => 'john@doe.com',
            'registration_form[password][first]' => 'ljksdn',
            'registration_form[password][second]' => 'ljksdn',
            'registration_form[address]' => 'XX rue de paradis',
            'registration_form[ville]' => 'Paris',
            'registration_form[zipCode]' => '75001'
        ]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('div.invalid-feedback', 'Votre mot de passe doit comporter au moins 6 caractères, une lettre majuscule, une lettre miniscule et 1 chiffre sans espace blanc');
    }

    public function testRegisterNewUserWithInvalidRepeatedPassword()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[prenom]' => 'John',
            'registration_form[nom]' => 'Doe',
            'registration_form[username]' => 'John Doe',
            'registration_form[age]' => 45,
            'registration_form[email]' => 'john@doe.com',
            'registration_form[password][first]' => 'Test1234',
            'registration_form[password][second]' => 'Kgkjhsk13',
            'registration_form[address]' => 'XX rue de paradis',
            'registration_form[ville]' => 'Paris',
            'registration_form[zipCode]' => '75001'
        ]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('div.invalid-feedback', 'Les valeurs ne correspondent pas.');
    }
}
