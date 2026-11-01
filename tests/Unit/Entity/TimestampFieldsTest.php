<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Epic;
use App\Tests\Factory\EpicFactory;
use PHPUnit\Framework\TestCase;

final class TimestampFieldsTest extends TestCase
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
