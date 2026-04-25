<?php

declare(strict_types=1);

namespace MeowList\Domain\Todo;

class TodoItem
{
    private ?int $id = null;
    private int $userId;
    private string $text;
    private bool $isDone;
    private \DateTimeImmutable $createdAt;

    public function __construct(int $userId, string $text)
    {
        $this->userId = $userId;
        $this->text = $text;
        $this->isDone = false;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function markAsDone(): void
    {
        $this->isDone = true;
    }

    public function markAsUndone(): void
    {
        $this->isDone = false;
    }
}
