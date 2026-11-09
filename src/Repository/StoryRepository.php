<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Story;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Story> */
class StoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Story::class);
    }

    /** @return array<Story> */
    public function findWithTag(Tag $tag): array {
        /** @var array<Story> */
        return $this->createQueryBuilder('s')
            ->innerJoin('s.storyTags', 'tt')
            ->where('tt.tag = :tag')
            ->setParameter('tag', $tag)
            ->orderBy('s.name', \SortDirection::Ascending)
            ->getQuery()
            ->getResult()
        ;
    }
}
