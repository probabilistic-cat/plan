<?php

declare(strict_types=1);

namespace App\Entity\Common;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TimestampFields
{
    #[ORM\Column(type: Types::BIGINT)]
    private(set) int $createdAt;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private(set) ?int $updatedAt = null;

    #[ORM\PrePersist]
    public function onPersistTs(): void {
        $this->createdAt = time();
    }

    #[ORM\PreUpdate]
    public function onUpdateTs(): void {
        $this->updatedAt = time();
    }
}
