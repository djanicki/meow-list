<?php

declare(strict_types=1);

namespace MeowList\Tests\Domain\Todo;

use MeowList\Domain\Todo\TodoItem;
use PHPUnit\Framework\TestCase;

class TodoItemTest extends TestCase
{
    public function testTodoItemIsCreatedSuccessfully(): void
    {
        $userId = 1;
        $text = 'Buy milk';

        $todoItem = new TodoItem($userId, $text);

        $this->assertNull($todoItem->getId());
        $this->assertSame($userId, $todoItem->getUserId());
        $this->assertSame($text, $todoItem->getText());
        $this->assertFalse($todoItem->isDone());
        $this->assertInstanceOf(\DateTimeImmutable::class, $todoItem->getCreatedAt());
    }

    public function testTodoItemCanBeMarkedAsDoneAndUndone(): void
    {
        $todoItem = new TodoItem(1, 'Task');

        $todoItem->markAsDone();
        $this->assertTrue($todoItem->isDone());

        $todoItem->markAsUndone();
        $this->assertFalse($todoItem->isDone());
    }
}
