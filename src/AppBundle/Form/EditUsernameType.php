<?php
/**
 * Created by PhpStorm.
 * User: fullcontroll
 * Date: 17.5.23
 * Time: 09.48
 */

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUsernameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('username', TextType::class, array(
              'label' => 'Username',
               'label_attr' => array(
                   'class' => 'cols-sm-2 control-label',
               ),
               'attr' => array(
                   'class' => 'form-control'
               ),
           ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => User::class,
        ));
    }
}