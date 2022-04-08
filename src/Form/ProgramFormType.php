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

use App\Entity\Programs;


class ProgramFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateTimeType::class, array('attr' => array('date' => 'Fecha'),
            'constraints' => array(
                new NotBlank(array("message" => "Please provide your name")),
            )
        ))
            ->add('teacher', EntityType::class, array(
                'class' => 'App:User',

            ))
            ->add('course', EntityType::class, array(
                'class' => 'App:Course',

            ))
            ->add('type', EntityType::class, array(
                'class' => 'App:QuestionnaireGroup',

            ))
            ->add('detail', TextType::class, array('attr' => array('detail' => 'Detalle'),
                'constraints' => array(
                    new NotBlank(array("message" => "Por favor ingrese una descripcion del programa")),
                )
            ))
            ->add('status', IntegerType::class, array('attr' => array('status' => 'Your message here'),
                'constraints' => array(
                    new NotBlank(array("message" => "Estado del programa invalido")),
                )
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
            'data_class' => 'App\Entity\Programs'
        ]);
    }

    public function getName()
    {
        return 'tecnotek_expediente_programformtype';
    }
}
