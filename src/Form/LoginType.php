<?php

namespace App\Form;

use App\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emailLogin')
            ->add('mdpLogin')
            ->add('blockedLogin',CheckboxType::class, [
                'label' => 'Blocked',
                'attr'=> [ 'disabled' => true ]
            ])
            ->add('blockedMessage' ,TextType::class, [
        'label' => 'Blocked message',
        'attr'=> [ 'readonly' => true ]
    ])
            ->add('blockedDuree',DateType::class, [
                'label' => 'Blocked Duree',
                'attr'=> [ 'readonly' => true ]
            ])
            ->add('idLogin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Login::class,
        ]);
    }
}
