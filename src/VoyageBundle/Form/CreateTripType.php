<?php

namespace VoyageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\FileType;

class CreateTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titrevoyage',TextType::class, array('attr' => array('placeholder' => 'Titre du voyage',
                                                                 'class'       => 'title-new-trip form-control')))
            ->add('datedebutvoyage',DateType::class,array(
                'widget' => 'single_text',
                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['placeholder' => 'Date de début','class' => 'js-datepicker form-control'],
                'label'=>' '
            ))
            ->add('descriptionvoyage',TextareaType::class, array('label'=>'Description du voyage',
                                                                'label_attr' => array('class' => 'control-label col-sm-2')))
            ->add('imagefile',VichImageType::class, array(
                'label'=>' ',
                'required' => false,
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VoyageBundle\Entity\Voyages',
        ));
    }

    public function getName()
    {
        return 'voyage_bundle_create_trip_type';
    }
}
