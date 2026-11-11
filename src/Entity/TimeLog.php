<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'time_log')]
#[ORM\Index(name: 'ix__time_log__user_id', columns: ['user_id'])]
#[ORM\Index(name: 'ix__time_log__task_id', columns: ['task_id'])]
#[ORM\HasLifecycleCallbacks]
final class TimeLog
{
    use IdField;
    use TimestampFields;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    public User $user;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'timeLogs')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    public Task $task;

    #[ORM\Column(type: Types::SMALLINT)]
    public int $minutes;
}
