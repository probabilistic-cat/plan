<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_tag')]
#[ORM\Index(name: 'ix__task_tag__tag_id', columns: ['tag_id'])]
final class TaskTag
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'taskTags')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    public Task $task;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Tag::class)]
    #[ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    public Tag $tag;
}
