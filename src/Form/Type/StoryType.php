<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Epic;
use App\Entity\Story;
use App\Entity\Tag;
use App\Form\Type\Common\EntityTag;
use App\Form\Type\Common\FormConsts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/** @extends AbstractType<Story> */
final class StoryType extends AbstractType
{
    use EntityTag;

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(message: FormConsts::NAME_REQUIRED_MESSAGE),
                    new Assert\Length(max: FormConsts::NAME_MAX_LENGTH),
                ],
            ])
            ->add('description', TextType::class, [
                'required' => false,
            ])
            ->add('epic', EntityType::class, [
                'class' => Epic::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'None',
                'attr' => ['class' => 'w-100'],
            ])
            ->add('storyTags', StoryTagsType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'w-100'],
                'query_builder' => $this->getTagsQb(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Story::class,
        ]);
    }
}
