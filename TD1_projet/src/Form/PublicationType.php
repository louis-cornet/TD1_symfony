<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

class PublicationType extends AbstractType
{

    public function __construct(
        private Security $security
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, ['attr' => ['minlength' => 4, 'maxlength' => 200]])
            ->add('publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $user = $this->security->getUser();
        if ($user != null){
            $group = $user->isPremium() ? 'publication:write:premium' : 'publication:write:normal';
            $resolver->setDefaults([
                'data_class' => Publication::class,
                'validation_groups' => ['Default', $group]
            ]);
        }
    }
}
