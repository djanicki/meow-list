<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Query;

class TodoItemDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public readonly bool $isDone
    ) {
    }
}
