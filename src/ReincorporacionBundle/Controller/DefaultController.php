<?php

namespace ReincorporacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
