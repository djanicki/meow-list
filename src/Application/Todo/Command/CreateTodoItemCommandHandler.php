<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Command;

use MeowList\Domain\Todo\TodoItem;
use MeowList\Domain\Todo\TodoItemRepositoryInterface;

class CreateTodoItemCommandHandler
{
    public function __construct(
        private readonly TodoItemRepositoryInterface $repository
    ) {
    }

    public function __invoke(CreateTodoItemCommand $command): void
    {
        $todoItem = new TodoItem($command->userId, $command->text);
        $this->repository->save($todoItem);
    }
}
