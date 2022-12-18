<?php

namespace App\Form;

use App\Entity\Recomponse;
use App\Entity\RecomponseLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RecomponseLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('idRecomponse',EntityType::class ,[
                        'class'=>Recomponse::class,
                       'choice_label'=>'nomRecomponse',
                'label' => 'Recomponse',
            ])
            ->add('idLivre')
            ->add('quantite')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecomponseLivre::class,
        ]);
    }
}
