<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task')]
#[ORM\Index(name: 'ix__task__user_id', columns: ['user_id'])]
#[ORM\Index(name: 'ix__task__task_status_id', columns: ['task_status_id'])]
#[ORM\HasLifecycleCallbacks]
final class Task
{
    use IdField;
    use TimestampFields;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'NO ACTION')]
    public User $user;

    #[ORM\ManyToOne(targetEntity: TaskStatus::class)]
    #[ORM\JoinColumn(name: 'task_status_id', referencedColumnName: 'id', nullable: false, onDelete: 'NO ACTION')]
    public TaskStatus $taskStatus;

    #[ORM\Column(type: Types::TEXT)]
    public string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    public ?int $estMinutes = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    public ?int $dueDate = null;
}
