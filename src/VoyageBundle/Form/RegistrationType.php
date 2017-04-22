<?php

namespace VoyageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',TextType::class ,array('label' => 'Last name'))
                ->add('prenom',TextType::class,array('label' => 'Fiest name'))
                ->add('nationalite',CountryType::class,array(
                    'preferred_choices' => array('US') ,
                    'label' => 'Nationality'
                ))
                ->add('descriptionprofil',TextareaType::class, array(
                    'label'=>'Personal description',
                    'required' => false
                ))
                ->add('imagefile',VichImageType::class, array('label'=>' ','required' => false ))
                ->add('imagefilecover',VichImageType::class, array('label'=>' ','required' => false ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VoyageBundle\Entity\Utilisateurs',
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';

    }

    public function getName()
    {
        return 'app_user_registration';
    }

}
