<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventFormType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('event.form.title')
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('event.form.description')
                ]
            ])
            ->add('startsAt', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('endsAt', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('rsvpBy', DateTimeType::class, [
                'label' => $this->translator->trans('event.form.rsvp-date'),
                'widget' => 'single_text',
            ])
            ->add('isOnline', CheckboxType::class, [
                'required' => false
            ])
            ->add('link', UrlType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('event.form.online-link')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
