<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 3/18/17
 * Time: 3:15 PM
 */

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
//        public function buildForm(FormBuilderInterface $builder, array $options)
//        {
//            $builder
//                ->add('plainPassword', RepeatedType::class, array(
//                    'type' => PasswordType::class,
//                    'first_options' => array(
//                        'label_attr' => array(
//                            'class' => 'cols-sm-2 control-label',
//                        ),
//                        'label' => 'Password',
//                        'attr' => array(
//                            'class' => 'form-control'
//                        ),
//                    ),
//                    'second_options' => array(
//                        'label_attr' => array(
//                            'class' => 'cols-sm-2 control-label',
//                        ),
//                        'label' => 'Repeat password',
//                        'attr' => array(
//                            'class' => 'form-control'
//                        ),
//                    ),
//                ));
//        }
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => User::class,
//            'validation_groups' => ['Default'],
//        ));
}