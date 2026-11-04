<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\EpicTag;
use App\Entity\StoryTag;
use App\Entity\TaskTag;
use App\Form\DataTransformer\EntityTagTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/** @extends AbstractType<ArrayCollection<int, EpicTag|StoryTag|TaskTag>> */
abstract class EntityTagsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->addModelTransformer($this->getTransformer());
    }

    public function getParent(): string {
        return EntityType::class;
    }

    abstract protected function getTransformer(): EntityTagTransformer;
}
