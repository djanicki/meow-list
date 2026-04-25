<?php

namespace MeowList\Domain\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;
    public function findByEmail(string $email): ?User;
}
