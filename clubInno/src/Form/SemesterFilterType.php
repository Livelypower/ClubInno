<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 5/16/2019
 * Time: 11:53 AM
 */

namespace App\Form;


use App\Entity\Semester;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SemesterFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('semesters', EntityType::class, [
                'label' => 'Des semestres',
                'class' => Semester::class,
                'choice_label' => 'stringified',
                'multiple' => true,
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Filtrer']);

    }
}