<?php

declare(strict_types=1);

namespace App\Application\Todo\Query;

class GetTodosForUserQuery
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
