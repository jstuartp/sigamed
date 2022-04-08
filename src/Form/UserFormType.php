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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use App\Entity\User;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, array('attr' => array('firstname' => 'Nombre'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor ingrese un nombre")),
            )
        ))
        ->add('lastname', TextType::class, array('attr' => array('lastname' => 'Apellidos'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor ingrese un apellido")),
            )
        ))
        ->add('username', TextType::class, array('attr' => array('username' => 'Usuario'),
            'constraints' => array(
                new NotBlank(array("message" => "Por favor ingrese un nombre de usuario")),
            )
        ))
        ->add('email', TextType::class, array('attr' => array('email' => 'Correo')
        ))
        ->add('active', CheckboxType::class, array(
            'label'    => 'Activo:',
            'required' => false,
        ))
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
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
            'data_class' => 'App\Entity\User'
        ]);
    }

    public function getName()
    {
        return 'tecnotek_expediente_userformtype';
    }
}
