<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\TimeLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/** @extends AbstractType<TimeLog> */
final class TimeLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('minutes', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(message: 'Minutes is required.'),
                    new Assert\Range(min: 1),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => TimeLog::class,
        ]);
    }
}
