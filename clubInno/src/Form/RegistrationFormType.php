<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un adresse email s\'il vous plaît'
                    ]),
                    new Email([
                        'message' => 'Entrez un adresse email valide s\'il vous plaît'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prénom s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                    new Regex([
                        'pattern' => "/^[a-zA-Z'-]+$/",
                        'message' => 'Votre prénom doit comporter seulement des lettres et les tirets'
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                    new Regex([
                        'pattern' => "/^[a-zA-Z' -]+$/",
                        'message' => 'Votre nom doit comporter seulement des lettres, des espaces et les tirets'
                    ])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe s\'il vous plaît',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre mot de passe doit comporter au moins 3 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('orientation', TextType::class, ['label' => 'Orientation'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
