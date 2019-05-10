<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 5/2/2019
 * Time: 11:57 AM
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class BlogAddFilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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