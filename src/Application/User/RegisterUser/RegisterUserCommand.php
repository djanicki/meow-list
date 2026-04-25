<?php

namespace MeowList\Application\User\RegisterUser;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $plainPassword,
    ) {
    }
}
