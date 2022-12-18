<?php

namespace App\Form;

use App\Entity\AchatRecomponse;
use App\Entity\Recomponse;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatRecomponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUtilisateur',EntityType::class ,[
                'class'=>Utilisateur::class,
                'choice_label'=>'nomUtilisateur',
                'label' => 'Utilisateur',
            ])
            ->add('idRecomponse',EntityType::class ,[
                'class'=>Recomponse::class,
                'choice_label'=>'nomRecomponse',
                'label' => 'Recomponse',
            ])
            ->add('quantite')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AchatRecomponse::class,
        ]);
    }
}
