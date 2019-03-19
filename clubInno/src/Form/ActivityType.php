<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/7/2019
 * Time: 2:16 PM
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tag;


class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('tags', EntityType::class, [
                // looks for choices from this entity
                'class' => Tag::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => true
                // 'expanded' => true,
            ])
            ->add('maxAmountStudents', NumberType::class, ['label' => 'Max étudiants'])
            ->add('registrationDeadline', DateType::class,
                [
                    'label' => 'Date limite d\'inscription',
                    'widget' => 'single_text',
                    'html5' => false,
                    'format' => 'mm/dd/yyyy'
                ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}