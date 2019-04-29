<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2019
 * Time: 15:32
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom du tag doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}