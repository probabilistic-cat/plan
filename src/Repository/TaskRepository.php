<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Task> */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Task::class);
    }

    /** @return array<Task> */
    public function findWithTag(Tag $tag): array {
        /** @var array<Task> */
        return $this->createQueryBuilder('t')
            ->innerJoin('t.taskTags', 'tt')
            ->where('tt.tag = :tag')
            ->setParameter('tag', $tag)
            ->orderBy('t.name', \SortDirection::Ascending)
            ->getQuery()
            ->getResult()
        ;
    }
}
