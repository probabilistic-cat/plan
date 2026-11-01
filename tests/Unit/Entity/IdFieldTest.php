<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Epic;
use App\Tests\Factory\EpicFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

final class IdFieldTest extends TestCase
{
    public function testOnPersistId(): void {
        /** @var Epic $epic */
        $epic = EpicFactory::createOne();
        $epic->onPersistId();
        $this->assertInstanceOf(UuidV7::class, $epic->id);
    }
}
