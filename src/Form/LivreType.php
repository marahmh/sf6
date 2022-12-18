<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreLivre')
            ->add('descriptionLivre')
            ->add('photoLivre',FileType::class,[
                'label'=>false,
                'multiple'=>false
            ])
            ->add('contenuLivre',FileType::class,[
                'label'=>false,
                'multiple'=>false
            ])
            ->add('prixLivre')
            ->add('idCategorieLivre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
