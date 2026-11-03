<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Epic;
use App\Tests\Factory\EpicFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV7;

final class IdFieldTest extends KernelTestCase
{
    public function testOnPersistId(): void {
        /** @var Epic $epic */
        $epic = EpicFactory::createOne();
        $epic->onPersistId();
        $this->assertInstanceOf(UuidV7::class, $epic->id);
    }
}
