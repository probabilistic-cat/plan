<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\DataTransformer\StoryTagTransformer;

final class StoryTagsType extends EntityTagsType
{
    protected function getTransformer(): StoryTagTransformer {
        return new StoryTagTransformer();
    }
}
