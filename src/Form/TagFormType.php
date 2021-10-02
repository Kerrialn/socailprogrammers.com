<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagFormType extends AbstractType
{
    const BEGINNER = "beginner";
    const INTERMEDIATE = "intermediate";
    const ADVANCED = "advanced";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'php, devops, magic, etc..'
                ]
            ])
            ->add('level', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Beginner' => self::BEGINNER,
                    'Intermediate' => self::INTERMEDIATE,
                    'Advanced' => self::ADVANCED,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
