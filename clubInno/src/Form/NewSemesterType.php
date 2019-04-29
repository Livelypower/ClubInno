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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class NewSemesterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = array();

        for($i = 2018; $i < 2040; $i++){
            $years[$i] = $i;
        }


        $builder
            ->add('startYear', ChoiceType::class, [
                'label' => 'L\'année de début',
                'choices' => $years
            ])
            ->add('endYear', ChoiceType::class, [
                'label' => 'L\'année de fin',
                'choices' => $years
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}