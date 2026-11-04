<?php

declare(strict_types=1);

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

trait IdField
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private(set) Uuid $id;

    public function isNew(): bool {
        return !isset($this->id);
    }

    #[ORM\PrePersist]
    public function onPersistId(): void {
        $this->id = Uuid::v7();
    }
}
