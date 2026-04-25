<?php

declare(strict_types=1);

namespace MeowList\Infrastructure\Persistence\Doctrine\Repository;

use MeowList\Domain\Todo\TodoItem;
use MeowList\Domain\Todo\TodoItemRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TodoItem>
 */
class DoctrineTodoItemRepository extends ServiceEntityRepository implements TodoItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoItem::class);
    }

    public function save(TodoItem $todoItem): void
    {
        $this->getEntityManager()->persist($todoItem);
        $this->getEntityManager()->flush();
    }

    public function remove(TodoItem $todoItem): void
    {
        $this->getEntityManager()->remove($todoItem);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?TodoItem
    {
        return $this->find($id);
    }

    /**
     * @return TodoItem[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->findBy(['userId' => $userId], ['createdAt' => 'ASC']);
    }
}
