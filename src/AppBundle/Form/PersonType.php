<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class PersonType extends AbstractType
{
    /**
     * function to build form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName',TextType::class)
        ->add('lastName',TextType::class)
        ->add('address',TextType::class)
        ->add('zip',IntegerType::class)
        ->add('city',TextType::class)
        ->add('country',CountryType::class)
        ->add('phoneNumber',TelType::class)
        ->add('birthday',BirthdayType::class)
        ->add('email',EmailType::class)
        ->add('picture',FileType::class,
            [
                'required'=>false,
                "data_class"=>null,
                "attr"=>
                [
                    'accept'=>"image/*"
                ]
                
            ]);
    }
    
    /**
     * set configuration
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person'
        ));
    }

    /**
     * get Block prefix
     */
    public function getBlockPrefix()
    {
        return 'appbundle_person';
    }

}
