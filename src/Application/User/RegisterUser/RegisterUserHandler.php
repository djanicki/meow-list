<?php

namespace App\Application\User\RegisterUser;

use App\Domain\User\Exception\UserAlreadyExistsException;
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
        if ($this->userRepository->findByEmail($command->email) !== null) {
            throw new UserAlreadyExistsException(sprintf('User with email "%s" already exists.', $command->email));
        }

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
