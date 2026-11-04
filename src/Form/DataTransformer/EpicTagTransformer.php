<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\EpicTag;

final class EpicTagTransformer extends EntityTagTransformer
{
    protected function createEntityTag(): EpicTag {
        return new EpicTag();
    }
}
