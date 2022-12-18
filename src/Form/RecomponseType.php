<?php

namespace App\Form;

use App\Entity\Recomponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecomponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomRecomponse')
            ->add('descriptionRecomponse')
            ->add('photoRecomponse',FileType::class,
               [
                   'data_class' => null,
                   'label'=>'importer photo',
                   'required' => false
               ] )
            ->add('prixRecomponse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recomponse::class,
        ]);
    }
}
