<?php

declare(strict_types=1);

namespace MeowList\Application\Todo\Query;

use MeowList\Domain\Todo\TodoItemRepositoryInterface;

class GetTodosForUserQueryHandler
{
    public function __construct(
        private readonly TodoItemRepositoryInterface $repository
    ) {
    }

    /**
     * @return TodoItemDto[]
     */
    public function __invoke(GetTodosForUserQuery $query): array
    {
        $items = $this->repository->findByUserId($query->userId);

        $dtos = [];
        foreach ($items as $item) {
            $dtos[] = new TodoItemDto(
                $item->getId(),
                $item->getText(),
                $item->isDone()
            );
        }

        return $dtos;
    }
}
