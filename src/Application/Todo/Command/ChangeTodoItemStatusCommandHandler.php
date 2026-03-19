<?php

declare(strict_types=1);

namespace App\Application\Todo\Command;

use App\Domain\Todo\TodoItemRepositoryInterface;

class ChangeTodoItemStatusCommandHandler
{
    public function __construct(
        private readonly TodoItemRepositoryInterface $repository
    ) {
    }

    public function __invoke(ChangeTodoItemStatusCommand $command): void
    {
        $todoItem = $this->repository->findById($command->todoItemId);

        if (!$todoItem || $todoItem->getUserId() !== $command->userId) {
            throw new \RuntimeException('Todo item not found or unauthorized');
        }

        if ($command->isDone) {
            $todoItem->markAsDone();
        } else {
            $todoItem->markAsUndone();
        }

        $this->repository->save($todoItem);
    }
}
