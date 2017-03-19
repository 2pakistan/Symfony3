<?php

namespace VoyageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use VoyageBundle\Entity\Etapes;

class CreateStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptionetape',TextareaType::class, array('label'=>'Description de l\'etape',
                                                                'label_attr' => array('class' => 'control-label col-sm-2'),
                                                                'attr' => array( 'class'       => 'form-control col-sm-10')))

            ->add();

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Etapes::class,
        ));
    }

    public function getName()
    {
        return 'voyage_bundle_create_step_type';
    }
}
