<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_utilisateur')
            ->add('date_naissance_utilisateur',DateType::Class, array(
                'label'=>false,
                'widget' => 'choice',
                'years' => range(date('Y')-18, date('Y')-100),
                'data' => new \DateTime(),

            ))
            ->add('photo_utilisateur',FileType::class,[
                'label'=>false,
                'multiple'=>false,
                'mapped'=>false,
                'required'=>false
            ])
            ->add('email_login',null,array(
                'attr'=> array('novalidate'=>'novalidate'),
            ))
            ->add('numero_utilisateur',null,array(
                'attr'=> array('novalidate'=>'novalidate'),
            ))
            ->add('typeUtilisateur', ChoiceType::class, [
                'label' => 'Role:',
                'placeholder' => 'Role',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'Utilisateur' => 1  ,
                    'Ecrivain' => 2 ,
                ],

            ])
            ->add('mdp_login', PasswordType::class )
            // ,[
            //                // instead of being set onto the object directly,
            //                // this is read and encoded in the controller
            //                'mapped' => false,
            //                'constraints' => [
            //                    new EqualTo('mot_de_passe'),]
            //            ])
            ->add('confirme_mot_de_passe',PasswordType::class)
            ->add('captcha', CaptchaType::class);
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
