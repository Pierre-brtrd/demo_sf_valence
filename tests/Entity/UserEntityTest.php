<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Utils\AssertTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class UserEntityTest extends KernelTestCase
{
    use AssertTestTrait;

    protected $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testRepositoryUserCount()
    {
        $users = $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserFixtures.yaml',
        ]);

        $users = self::getContainer()->get(UserRepository::class)->count([]);

        $this->assertEquals(11, $users);
    }

    public function getEntity(): User
    {
        return (new User())
            ->setEmail('test@test.com')
            ->setUsername('User Test')
            ->setNom('User')
            ->setPrenom('Test')
            ->setPassword('Test1234')
            ->setAddress('XX rue de test')
            ->setZipCode('75000')
            ->setVille('Paris')
            ->setAge(25);
    }

    public function testValideEntityUser()
    {
        $this->assertHasErrors($this->getEntity());
    }

    public function testNonUniqueEmailEntityUser()
    {
        $user = $this->getEntity()
            ->setEmail('admin@example.com');

        $this->assertHasErrors($user, 1);
    }

    public function testInvalideEmailWhithoutAtEntityUser()
    {
        $user = $this->getEntity()
            ->setEmail('ksdhjbfgkjbsdf');

        $this->assertHasErrors($user, 1);
    }

    public function testInvalideEmailLengthEntityUser()
    {
        $user = $this->getEntity()
            ->setEmail('ksjdbfkjsdhfkjsdhfkjsdhfkjsdhfkjsdhfgkjsdhfgksjdhfksjdhfgkhfgkjhfkjhfgkshgkjshfkjshkjshfkjhsfkjhsdkjfhskfhifghihgouhghoihhoihjnkjnkejnkjhkjhjkehkjhkejnkjbnkjfnkjqfkjshfkjhqsfkjhsdfkjsdhfksjhfkjsdhfkhjfkjhdskjdsngkjhsdgksjdfksjdfksdhfjkhsdbfkjhsdfkbjsdfkjhsfkjhsfihsdkfjhskdjfhskjdhf@test.com');

        $this->assertHasErrors($user, 2);
    }

    public function testNonUniqueUsernameEntityUser()
    {
        $user = $this->getEntity()
            ->setUsername('User Admin');

        $this->assertHasErrors($user, 1);
    }

    public function testMinLengthUsernameEntityUser()
    {
        $user = $this->getEntity()
            ->setUsername('');

        $this->assertHasErrors($user, 1);
    }

    public function testMaxLengthUsernameEntityUser()
    {
        $user = $this->getEntity()
            ->setUsername('kshdjfkjhsdfkjsdhf skdjfhsdkjfhskdjfh sdkjfhskdjfhskjfhskjfh skjfh skjdfhkjshfkjshdfkjshdfkjhsdfkjshdfkjhsdfkjhsdkfj skdjfh skdjfhskjfhs kfkjsdhfkjsdhfkjshfkjsdhfkshjfskjhfkjsdhfkjshdfkjshdfkjhsdfkjhsdkjfhskdjhfkjsdhfkjsdhfkjsdhfkjshdf');

        $this->assertHasErrors($user, 1);
    }

    public function testBlankPrenomEntityUser()
    {
        $user = $this->getEntity()
            ->setPrenom('');

        $this->assertHasErrors($user, 1);
    }

    public function testMaxLengthPrenomEntityUser()
    {
        $user = $this->getEntity()
            ->setPrenom('sdkfjhskdjfhksdjfhsdkjfhsdfksd fkjsdhfkjsdhfkjsdhfkjsdhfkjshdfkshdfkjh ksjdhf');

        $this->assertHasErrors($user, 1);
    }

    public function testBlankNomEntityUser()
    {
        $user = $this->getEntity()
            ->setNom('');

        $this->assertHasErrors($user, 1);
    }

    public function testMaxLengthNomEntityUser()
    {
        $user = $this->getEntity()
            ->setNom('sdkfjhskdjfhksdjfhsdkjfhsdfksd fkjsdhfkjsdhfkjsdhfkjsdhfkjshdfkshdfkjh ksjdhf');

        $this->assertHasErrors($user, 1);
    }

    public function testInvalideAgeEntityUser()
    {
        $user = $this->getEntity()
            ->setAge(160);

        $this->assertHasErrors($user, 1);
    }

    public function testMinAgeEntityUser()
    {
        $user = $this->getEntity()
            ->setAge(0);

        $this->assertHasErrors($user, 1);
    }

    public function testInvalidZipCodeEntityUser()
    {
        $user = $this->getEntity()
            ->setZipCode('kjdhsfkjsh');

        $this->assertHasErrors($user, 1);
    }

    public function testMaxLengthZipCodeEntityUser()
    {
        $user = $this->getEntity()
            ->setZipCode('kjdhsfkjshsdkfjhsdkjfghskdjfhkhjsdfkjshdfkjshdfkjhsdkjfh');

        $this->assertHasErrors($user, 2);
    }
}
