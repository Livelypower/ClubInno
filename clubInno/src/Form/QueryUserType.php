<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/03/2019
 * Time: 15:32
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QueryUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'empty_data' => '',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Surnom',
                'empty_data' => '',
            ])
            ->add('save', SubmitType::class, ['label' => 'Chercher']);
    }
}