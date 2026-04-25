<?php

namespace MeowList\Tests\Unit\Domain\User;

use MeowList\Domain\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserDefaults(): void
    {
        $user = new User();

        $this->assertNotNull($user->getCreatedAt());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertNull($user->getId());
    }

    public function testSettersAndGetters(): void
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());

        $password = 'hashed_password';
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());

        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testTimestampUpdates(): void
    {
        $user = new User();
        $now = new \DateTimeImmutable();

        $user->setLastLogin($now);
        $this->assertEquals($now, $user->getLastLogin());

        $user->setLastFailedLogin($now);
        $this->assertEquals($now, $user->getLastFailedLogin());
    }
}
