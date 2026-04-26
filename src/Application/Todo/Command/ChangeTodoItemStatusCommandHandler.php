<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Command;

use MeowList\Domain\Todo\TodoItemRepositoryInterface;

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
            $this->repository->save($todoItem);
            return;
        }

        $todoItem->markAsUndone();
        $this->repository->save($todoItem);
    }
}
