<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/03/2019
 * Time: 10:32
 */

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\CallbackTransformer;


class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'empty_data' => ''
                ])
            ->add('body', TextareaType::class, [
                'label' => 'Contenu',
                'empty_data' => ''
                ])
            ->add('activity', EntityType::class, [
                // looks for choices from this entity
                'class' => Activity::class,

                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => false
                // 'expanded' => true,
            ])
            ->add('files', FileType::class, [
                'mapped' => true,
                'required' => false,
                'multiple' => true,
                'label' => 'Files'
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);

        $builder->get('files')
            ->addModelTransformer(new CallbackTransformer(
                function ($filenames) {
                    $files = array();
                    if($filenames == null || empty($filenames)){
                        return null;
                    }
                    foreach ($filenames as $filename){
                        array_push($files, new File($filename));
                    }
                    return $files;
                },
                function ($files) {
                    return;
                }
            ));
    }
}