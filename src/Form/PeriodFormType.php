<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use App\Entity\Period;

class PeriodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', TextType::class, array('attr' => array('name' => 'Nombre'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor escriba un nombre al periodo")),
            )
            ))
            ->add('year', IntegerType::class, array('attr' => array('year' => 'Escriba un año'),
                'constraints' => array(
                    new NotBlank(array("message" => "Año del periodo es neceario")),
                )
            ))
            ->add('actual', CheckboxType::class, array(
                'label'    => 'Periodo Actual',
                'required' => false,
            ))
            ->add('editable', CheckboxType::class, array(
                'label'    => 'Editable',
                'required' => false,
            ))
        ;
    }


    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Period'
        ]);
    }

    public function getName()
    {
        return 'tecnotek_expediente_periodformtype';
    }
}
