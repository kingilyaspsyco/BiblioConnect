<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur (ROLE_USER)' => 'ROLE_USER',
                    'Bibliothécaire (ROLE_LIBRARIAN)' => 'ROLE_LIBRARIAN',
                    'Administrateur (ROLE_ADMIN)' => 'ROLE_ADMIN',
                ],
                'label' => 'Rôle',
                'expanded' => false,
                'multiple' => false,
                'mapped' => false,
                'data' => $options['data']->getRoles()[0] ?? 'ROLE_USER',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
