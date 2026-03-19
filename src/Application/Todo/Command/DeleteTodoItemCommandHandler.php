<?php

declare(strict_types=1);

namespace App\Application\Todo\Command;

use App\Domain\Todo\TodoItemRepositoryInterface;

class DeleteTodoItemCommandHandler
{
    public function __construct(
        private readonly TodoItemRepositoryInterface $repository
    ) {
    }

    public function __invoke(DeleteTodoItemCommand $command): void
    {
        $todoItem = $this->repository->findById($command->todoItemId);

        if (!$todoItem || $todoItem->getUserId() !== $command->userId) {
            throw new \RuntimeException('Todo item not found or unauthorized');
        }

        $this->repository->remove($todoItem);
    }
}
