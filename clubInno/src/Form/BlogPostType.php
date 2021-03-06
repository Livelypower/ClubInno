<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/03/2019
 * Time: 10:32
 */

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un titre s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le titre du blog doit comporter au moins 3 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
                ])
            ->add('body', TextareaType::class, [
                'label' => 'Contenu',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un contenu s\'il vous plaît'
                    ]),
                ]
                ])
            ->add('activity', EntityType::class, [
                // looks for choices from this entity
                'class' => Activity::class,
                'label' => 'Activité',
                'choice_label' => 'name',
                // used to render a select box, check boxes or radios
                'multiple' => false
                // 'expanded' => true,
            ])
            ->add('files', FileType::class, [
                'mapped' => false,
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