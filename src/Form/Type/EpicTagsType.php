<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\DataTransformer\EpicTagTransformer;

final class EpicTagsType extends EntityTagsType
{
    protected function getTransformer(): EpicTagTransformer {
        return new EpicTagTransformer();
    }
}
