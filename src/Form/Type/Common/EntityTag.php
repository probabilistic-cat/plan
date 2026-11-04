<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

trait EntityTag
{
    private function getTagsQb(): callable {
        return static fn (EntityRepository $er): QueryBuilder
            => $er->createQueryBuilder('t')->orderBy('t.name', \SortDirection::Ascending)
        ;
    }
}
