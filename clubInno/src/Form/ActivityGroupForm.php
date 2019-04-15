<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29/03/2019
 * Time: 15:20
 */

namespace App\Form;


use App\Entity\Activity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class ActivityGroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => ''
            ])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('activity', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'name',
                'multiple' => false,
                'required' => true,
                'empty_data' => ''
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}