<?php
/**
 * Created by PhpStorm.
 * User: fullcontroll
 * Date: 17.5.23
 * Time: 09.52
 */

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
               'label' => 'Email Address',
                'label_attr' => array(
                  'class' => 'cols-sm-2 control-label'
                ),
                'attr' => 'form-control'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => User::class
        ));
    }
}