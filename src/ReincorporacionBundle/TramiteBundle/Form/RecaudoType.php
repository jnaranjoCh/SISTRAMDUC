<?php

namespace TramiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TramiteBundle\Entity\Recaudo;

class RecaudoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => false));
        $builder->add('file', null, array('label' => false, 'attr' => array('class' => 'form-control')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Recaudo::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    /*public function getBlockPrefix()
    {
        return 'tramitebundle_recaudo';
    }*/


}
