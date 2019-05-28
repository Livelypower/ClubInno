<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/22/2019
 * Time: 11:59 AM
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;


class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motivationLetterPath', FileType::class, [
                'required' => true,
                'label' => 'Lettre de motivation',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajoutez une lettre de motivation.'
                    ]),
                    New File([
                        'maxSize' => "2048k",
                        'maxSizeMessage' =>"Le fichier est trop gros!"
                    ])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Envoyez']);

    }
}