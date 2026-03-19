<?php

declare(strict_types=1);

namespace App\Domain\Todo;

interface TodoItemRepositoryInterface
{
    public function save(TodoItem $todoItem): void;

    public function remove(TodoItem $todoItem): void;

    public function findById(int $id): ?TodoItem;

    /**
     * @return TodoItem[]
     */
    public function findByUserId(int $userId): array;
}
