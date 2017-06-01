<?php

namespace ComisionRemuneradaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use TramiteBundle\Form\RecaudoType;
use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SolicitudComisionServicioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recaudos', CollectionType::class, array(
                    'label' => false,
                    'entry_type' => RecaudoType::class
                )
            )
        ;
        $builder
            ->add('submit', SubmitType::class, array(
                    'label' => "Enviar",
                'attr' => array('class' => 'save btn btn-primary'),
                )
            )
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SolicitudComisionServicio::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'comisionremuneradabundle_solicitudcomisionservicio';
    }


}
