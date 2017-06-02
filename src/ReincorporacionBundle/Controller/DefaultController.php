<?php

namespace ReincorporacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use ReincorporacionBundle\Entity\TramiteReincorporacion;

class DefaultController extends Controller
{
    /**
     * @Route("/reincorporacion-docente", name="reincorporacion")
     */
    public function mostrarInicioDeSolicitud()
    {
        return $this->render('ReincorporacionBundle::inicio-solicitud.html.twig');
    }
    
    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum", name="actualizar-curriculum")
     */ 
    public function mostrarActualizarCurriculum()
    {
        return $this->render('ReincorporacionBundle::actualizar-curriculum.html.twig');
    }
    
    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum/nueva-entrada", name="nueva-entrada-curriculum")
     */ 
    public function mostrarCrearEntradaEnCurriculum()
    {
        // Creacion de form aqui!
        return $this->render('ReincorporacionBundle::nueva-entrada-curriculum.html.twig');
    }
    

    /**
     * @Route("/reincorporacion-docente/subir-recaudos", name="subir-recaudos")
     */ 
    public function mostrarSubirRecaudos()
    {
        return $this->render('ReincorporacionBundle::subir-recaudos.html.twig');
    }

    /**
     * @Route("/reincorporacion-docente/verificar-datos", name="verificar-datos")
     */ 
    public function mostrarVerificarDatos()
    {
        return $this->render('ReincorporacionBundle::verificar-datos.html.twig');
    }

    /** 
     * @Route("/reincorporacion-docente/upload-recaudos", name="upload-recaudos")
     */
     public function uploadRecaudos()
     {
         // Validacion de form aqui!
         // This would help -> http://symfony.com/doc/current/forms.html
     }
}
