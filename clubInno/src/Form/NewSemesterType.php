<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12/04/2019
 * Time: 11:13
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewSemesterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startYear', TextType::class, ['label' => 'Début'])
            ->add('endYear', TextType::class, ['label' => 'Fin'])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}