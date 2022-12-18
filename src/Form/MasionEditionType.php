<?php

namespace App\Form;

use App\Entity\MasionEdition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MasionEditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresseMaisonEdition')
            ->add('photoMaisonEdition')
            ->add('descriptionMaisonEdition')
            ->add('nomMaisonEdition')
            ->add('idAdminMaisonEdition')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MasionEdition::class,
        ]);
    }
}
