<?php

namespace App\Form;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_utilisateur')
            ->add('numero_utilisateur')
            ->add('photo_utilisateur',FileType::class,[
                'label'=>false,
                'multiple'=>false,
                'mapped'=>false,
                'required'=>false
            ])
            ->add('email_login',null,array(
                'attr'=> array('novalidate'=>'novalidate'),
            ))
            ->add('anc_mdp_login',PasswordType::class)
            ->add('mdp_login', PasswordType::class )
            // ,[
            //                // instead of being set onto the object directly,
            //                // this is read and encoded in the controller
            //                'mapped' => false,
            //                'constraints' => [
            //                    new EqualTo('mot_de_passe'),]
            //            ])
            ->add('confirme_mot_de_passe',PasswordType::class)
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}