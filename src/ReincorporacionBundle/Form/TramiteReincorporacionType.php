<?php

namespace ReincorporacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use TramiteBundle\Form\RecaudoType;
use ReincorporacionBundle\Entity\TramiteReincorporacion;

class TramiteReincorporacionType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('recaudos', CollectionType::class, array(
                'label' => false,
                'entry_type' => RecaudoType::class
            )
        ); 
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TramiteReincorporacion::class,
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'reincorporacionbundle_tramitereincorporacion';
    }
}