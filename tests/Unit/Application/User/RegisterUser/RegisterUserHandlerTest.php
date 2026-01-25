<?php

namespace App\Tests\Unit\Application\User\RegisterUser;

use App\Application\User\RegisterUser\RegisterUserCommand;
use App\Application\User\RegisterUser\RegisterUserHandler;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserHandlerTest extends TestCase
{
    public function testHandlecreatesAndPersistsUser(): void
    {
        // Arrange
        $email = 'test@example.com';
        $plainPassword = 'password123';
        $hashedPassword = 'hashed_password_123';

        $command = new RegisterUserCommand($email, $plainPassword);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        // Expect hashing
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), $plainPassword)
            ->willReturn($hashedPassword);

        // Expect persistence
        $userRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function (User $user) use ($email, $hashedPassword) {
                return $user->getEmail() === $email && $user->getPassword() === $hashedPassword;
            }));

        $handler = new RegisterUserHandler($userRepository, $passwordHasher);

        // Act
        $user = $handler->handle($command);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($hashedPassword, $user->getPassword());
    }
}
