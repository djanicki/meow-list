<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Command;

class CreateTodoItemCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly string $text
    ) {
    }
}
