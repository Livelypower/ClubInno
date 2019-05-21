<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/7/2019
 * Time: 2:16 PM
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;


class ActivityFilesEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainImage', FileType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Image',
                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => "Entrez une image valide."
                    ]),
                    new File([
                        'maxSize' => "2048k",
                        'maxSizeMessage' => "Le fichier est trop gros!"
                    ])
                ]
            ])
            ->add('files', FileType::class, [
                'mapped' => true,
                'required' => false,
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