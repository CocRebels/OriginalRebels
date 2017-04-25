<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/25/17
 * Time: 1:22 PM
 */

namespace AppBundle\Form;


use AppBundle\Entity\Alliance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllianceCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Alliance name',
                'label_attr' => array(
                        'class' => 'cols-sm-2 control-label',
                ),
                'attr' => array(
                    'class' => 'form-control',
                ),
                ))
            ->add('description', TextareaType::class, array(
                'label' => 'Alliance description',
                'label_attr' => array(
                    'class' => 'cols-sm-2 control-label',
                ),
                'attr' => array(
                  'class' => 'form-control',
                    'rows' => '6',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Alliance::class,
        ));
    }
}