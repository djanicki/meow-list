<?php

namespace App\Application\User\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function handle(RegisterUserCommand $command): User
    {
        $user = new User();
        $user->setEmail($command->email);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $command->plainPassword
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->add($user);

        return $user;
    }
}
