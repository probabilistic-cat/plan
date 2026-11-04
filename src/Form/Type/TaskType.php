<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Story;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\User;
use App\Form\Type\Common\EntityTag;
use App\Form\Type\Common\FormConsts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/** @extends AbstractType<Task> */
final class TaskType extends AbstractType
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
            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Unassigned',
                'attr' => ['class' => 'w-100'],
            ])
            ->add('story', EntityType::class, [
                'class' => Story::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'None',
                'attr' => ['class' => 'w-100'],
            ])
            ->add('taskStatus', EntityType::class, [
                'class' => TaskStatus::class,
                'choice_label' => 'code',
                'required' => true,
                'attr' => ['class' => 'w-100'],
            ])
            ->add('priority', IntegerType::class, [
                'required' => true,
            ])
            ->add('estMinutes', IntegerType::class, [
                'required' => false,
                'label' => 'Estimated Minutes',
            ])
            ->add('dueDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'timestamp',
            ])
            ->add('taskTags', TaskTagsType::class, [
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
            'data_class' => Task::class,
        ]);
    }
}
