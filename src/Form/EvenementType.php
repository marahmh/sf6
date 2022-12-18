<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreEvenement')
            ->add('descriptionEvenement')
            ->add('dateEvenement',DateTimeType::class)
            ->add('image',FileType::class,[
                'label'=>false,
                'multiple'=>false,
                'mapped'=>false,
                'required'=>false
            ])
            ->add('typeEvenement')
            ->add('adresseEvenement',TextType::class,['attr' => array(
                    'readonly' => true,
                )])
            ->add('Latitude',HiddenType::class)
            ->add('longitude',HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
