<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\DataTransformer\TaskTagTransformer;

final class TaskTagsType extends EntityTagsType
{
    protected function getTransformer(): TaskTagTransformer {
        return new TaskTagTransformer();
    }
}
