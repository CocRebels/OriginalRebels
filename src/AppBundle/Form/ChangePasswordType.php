<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/10/17
 * Time: 6:54 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label_attr' => array(
                        'class' => 'cols-sm-2 control-label',
                    ),
                    'label' => 'Password',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ),
                'second_options' => array(
                    'label_attr' => array(
                        'class' => 'cols-sm-2 control-label',
                    ),
                    'label' => 'Repeat password',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ),
            ));
    }
}