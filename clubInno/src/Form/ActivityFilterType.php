<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 5/16/2019
 * Time: 11:53 AM
 */

namespace App\Form;


use App\Entity\Activity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ActivityFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activities', EntityType::class, [
                'label' => 'Des activités',
                'class' => Activity::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Filtrer']);
    }
}