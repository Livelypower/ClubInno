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


class ActivityMomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('location', TextType::class, ['label' => 'Lieu'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('startDate', DateType::class, [
                'label' => 'start date',
                'widget' => 'single_text',
                'format'=> 'MM/dd/yyyy'
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'start time',
                'html5' => false,
                'with_seconds' => false,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'end date',
                'widget' => 'single_text',
                'format'=> 'MM/dd/yyyy',
                'required' => false
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'end time',
                'html5' => false,
                'with_seconds' => false,
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('activityGroups', EntityType::class, [
                'class' => ActivityGroup::class,
                'choice_label' => 'name',
                'multiple' => true,
                'empty_data' => ''
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}