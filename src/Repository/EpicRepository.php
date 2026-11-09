<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Epic;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Epic> */
class EpicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Epic::class);
    }

    /** @return array<Epic> */
    public function findWithTag(Tag $tag): array {
        /** @var array<Epic> */
        return $this->createQueryBuilder('e')
            ->innerJoin('e.epicTags', 'et')
            ->where('et.tag = :tag')
            ->setParameter('tag', $tag)
            ->orderBy('e.name', \SortDirection::Ascending)
            ->getQuery()
            ->getResult()
        ;
    }
}
