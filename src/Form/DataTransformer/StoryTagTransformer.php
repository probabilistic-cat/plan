<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\StoryTag;

final class StoryTagTransformer extends EntityTagTransformer
{
    protected function createEntityTag(): StoryTag {
        return new StoryTag();
    }
}
