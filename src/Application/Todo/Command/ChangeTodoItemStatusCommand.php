<?php

declare(strict_types=1);

namespace App\Application\Todo\Command;

class ChangeTodoItemStatusCommand
{
    public function __construct(
        public readonly int $todoItemId,
        public readonly int $userId,
        public readonly bool $isDone
    ) {
    }
}
