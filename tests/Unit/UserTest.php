<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getUserEntity(): User
    {
        $user = new User();
        $user
            ->setEmail('test@test.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword('testpassword')
            ->setFirstName('testFisrtName')
            ->setLastName('testLastName')
            ->setCity('Paris')
            ->setPosteCode(75018)
            ->setSlug('slug-test');

        return $user;
    }
    public function testValidUser(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $user = $this->getUserEntity();

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testInValidUser(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $user = $this->getUserEntity();
        $user->setEmail('testpassword');

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(1, $errors);
    }
}
