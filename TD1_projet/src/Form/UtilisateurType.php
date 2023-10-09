<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class,
                ["constraints" => [
                    new Length(min: 4, max: 20, minMessage: 'Mdp trop court', maxMessage: 'vous devez renseigner un nom dutilisateur plus long'),
                ]])
            ->add('adresseEmail', EmailType::class)
            ->add('plainPassword', PasswordType::class,
                ['attr' => [
                    'minlength' => 4,
                    'maxlength' => 10
                ],
                "mapped" => false,
                "constraints" => [
                    new NotBlank(),
                    new NotNull(),
                    new Length(min: 8, max: 30, minMessage: 'Mdp trop court', maxMessage: 'Mdp trop long'),
                    new Regex(pattern : '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$#', message : 'au moins une minuscule, une majuscule et un chiffre')
                ]])
            ->add('fichierPhotoProfil', FileType::class,
                ["constraints" => [
                    new File(maxSize : '10M', extensions : ['jpg', 'png']),
                ],
                "mapped" => false,
                ])
            ->add('inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
