<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_status')]
#[ORM\HasLifecycleCallbacks]
final class TaskStatus
{
    use IdField;
    use TimestampFields;

    public const string TODO = 'todo';
    public const string INPROGRESS = 'in_progress';
    public const string COMPLETED = 'completed';

    #[ORM\Column(type: Types::TEXT)]
    public string $code;
}
