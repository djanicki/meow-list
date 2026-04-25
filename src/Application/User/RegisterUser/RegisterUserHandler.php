<?php

namespace MeowList\Application\User\RegisterUser;

use MeowList\Domain\User\Exception\UserAlreadyExistsException;
use MeowList\Domain\User\User;
use MeowList\Domain\User\UserRepositoryInterface;
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
