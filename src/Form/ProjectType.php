<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('decription')
            ->add('creationDate',  DateType::class, [ 'widget' => 'single_text'])
            ->add('startedAt' ,  DateType::class, [ 'widget' => 'single_text'])
            ->add('endedAt',  DateType::class, [ 'widget' => 'single_text'])
            ->add('place')
            ->add('frontPage')
            ->add('illustration', Filetype::class, ['label' => 'Illustration du projet', 'data_class' => null])
            ->add('creator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
