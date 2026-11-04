<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\TaskTag;

final class TaskTagTransformer extends EntityTagTransformer
{
    protected function createEntityTag(): TaskTag {
        return new TaskTag();
    }
}
