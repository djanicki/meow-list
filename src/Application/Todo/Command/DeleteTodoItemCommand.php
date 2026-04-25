<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Command;

class DeleteTodoItemCommand
{
    public function __construct(
        public readonly int $todoItemId,
        public readonly int $userId
    ) {
    }
}
