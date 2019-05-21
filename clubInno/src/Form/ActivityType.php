<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/7/2019
 * Time: 2:16 PM
 */

namespace App\Form;

use App\Entity\Semester;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tag;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ActivityType extends AbstractType
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
                        'min' => 3,
                        'minMessage' => 'Le nom de l\'activité doit comporter au moins 3 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'empty_data' => ''
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Des tags',
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'empty_data' => '',
                'invalid_message' => 'Sélectionnez au moins un tag.'

            ])
            ->add('maxAmountStudents', NumberType::class, [
                'label' => 'Max étudiants',
                'empty_data' => null,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez le nombre maximum d\'étudiants  s\'il vous plaît'
                    ]),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Entrez le nombre maximum d\'étudiants  s\'il vous plaît'
                    ])
                ],
                'invalid_message' => 'Entrez un nombre entier valide.'
            ])
            ->add('semester', EntityType::class, [
                'label' => 'Semestre',
                'class' => Semester::class,
                'choice_label' => 'stringified',
                'multiple' => false,
                'required' => true,
                'empty_data' => ''
            ])
            ->add('mainImage', FileType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Image',
                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => "Entrez une image valide."
                    ]),
                    new File([
                        'maxSize' => "2048k",
                        'maxSizeMessage' => "Le fichier est trop gros!"
                    ])
                ]
            ])
            ->add('files', FileType::class, [
                'mapped' => true,
                'required' => false,
                'multiple' => true,
                'label' => 'Des fichiers',
                'constraints' => [
                    new All([
                        New File([
                            'maxSize' => "2048k",
                            'maxSizeMessage' => "Un des fichiers est trop gros!"
                        ])
                    ])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}