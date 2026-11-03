<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Epic;
use App\Tests\Factory\EpicFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TimestampFieldsTest extends KernelTestCase
{
    public function testOnPersistTs(): void {
        /** @var Epic $epic */
        $epic = EpicFactory::createOne();
        $time = time();
        $epic->onPersistTs();
        $this->assertGreaterThanOrEqual($time, $epic->createdAt);
        $this->assertLessThan($time + 5, $epic->createdAt);
    }

    public function testOnUpdateTs(): void {
        /** @var Epic $epic */
        $epic = EpicFactory::createOne();
        $time = time();
        $epic->onUpdateTs();
        $this->assertGreaterThanOrEqual($time, $epic->updatedAt);
        $this->assertLessThan($time + 5, $epic->updatedAt);
    }
}
