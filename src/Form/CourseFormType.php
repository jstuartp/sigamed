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

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Course;
use App\Entity\User;

class CourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class, array('attr' => array('name' => 'Nombre'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor escriba un código al curso")),
            )
        ))
        ->add('name', TextType::class, array('attr' => array('name' => 'Nombre'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor escriba un nombre al curso")),
            )
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Normal' => 1,
                'Verano' => 2,
            ),
        ))
        ->add('requisit', TextType::class, array('attr' => array('requisit' => 'Requisitos'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('corequisite', TextType::class, array('attr' => array('corequisite' => 'Co-Requisitos'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('credit', TextType::class, array('attr' => array('credit' => 'Creditos'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('area', TextType::class, array('attr' => array('area' => 'Área'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('schedule', TextType::class, array('attr' => array('schedule' => 'Calendario'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('groupnumber', TextType::class, array('attr' => array('groupnumber' => 'Grupo'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('classroom', TextType::class, array('attr' => array('classroom' => 'Clase'),
            'constraints' => array(
                'required'   => false
            )

        ))
        ->add('room', TextType::class, array('attr' => array('room' => 'Cupo'),
            'constraints' => array(
                'required'   => false
            )
        ))
        ->add('section', ChoiceType::class, array(
            'choices'  => array(
                'Repertorios' => 1,
                'Cursos de servicio' => 2,
                'Primer Año' => 3,
                'Segundo Año' => 4,
                'Tercer Año' => 5,
                'Cursos Optativos Tercer Año' => 6,
                'Cuarto Año' => 7,
                'Quinto Año' => 8,
                'Trabajos Finales' => 9,
            ),
        ))
        ;

    }

    public function getName()
    {
        return 'tecnotek_expediente_courseformtype';
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
            'data_class' => 'App\Entity\Course'
        ]);
    }
}
