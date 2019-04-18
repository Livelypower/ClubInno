<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AccountEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un email s\'il vous plaît'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prénom s\'il vous plaît'
                    ])
                ]
                ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom s\'il vous plaît'
                    ])
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
