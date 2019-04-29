<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 4/4/2019
 * Time: 12:05 PM
 */

namespace App\Form;

use App\Entity\ActivityGroup;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class ActivityMomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom du moment doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
                ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un lieu s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le lieu du moment doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez une description s\'il vous plaît'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'La description du moment doit comporter au moins 2 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ])
                ]
            ])
            ->add('startDate', DateType::class, [
                'label' => 'La date de début',
                'widget' => 'single_text',
                'format'=> 'MM/dd/yyyy',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez la date de début s\'il vous plaît'
                    ])
                ]
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'L\'heure de début',
                'html5' => false,
                'with_seconds' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez l\'heure de début s\'il vous plaît'
                    ])
                ]
            ])
            ->add('endDate', DateType::class, [
                'label' => 'La date de fin',
                'widget' => 'single_text',
                'format'=> 'MM/dd/yyyy',
                'required' => false
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'L\'heure de fin',
                'html5' => false,
                'with_seconds' => false,
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('activityGroups', EntityType::class, [
                'label' => 'Des groupes d\'activités',
                'class' => ActivityGroup::class,
                'choice_label' => 'name',
                'multiple' => true,
                'empty_data' => '',
                'invalid_message' => 'Sélectionnez au moins une groupe.'
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}