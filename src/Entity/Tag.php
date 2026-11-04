<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tag')]
#[ORM\UniqueConstraint(name: 'iu__tag__name', columns: ['name'])]
#[ORM\HasLifecycleCallbacks]
final class Tag
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(type: Types::TEXT, length: 50, unique: true)]
    public string $name;
}
