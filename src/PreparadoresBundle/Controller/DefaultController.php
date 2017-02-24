<?php

namespace PreparadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/preparadores/apertura_concurso", name="apertura_concurso_index")
     */
    public function aperturaDeConcursoAction()
    {
        return $this->render('PreparadoresBundle::apertura_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/apertura_concurso/solicitar", name="solicitar")
     */
    public function solicitarAction()
    {
        return $this->render('PreparadoresBundle::solicitar_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/apertura_concurso/gestionar", name="gestionar_apertura")
     */
    public function gestionarAperturaAction()
    {
        return $this->render('PreparadoresBundle::gestionar_apertura.html.twig');
    }
    
    /**
     * @Route("/preparadores/concurso", name="concurso_index")
     */
    public function concursoAction()
    {
        return $this->render('PreparadoresBundle::concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/concurso/gestionar", name="gestionar_concurso")
     */
    public function gestionarConcursoAction()
    {
        return $this->render('PreparadoresBundle::gestionar_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/renuncia_preparador", name="renuncia_preparador_index")
     */
    public function renunciaPreparadorAction()
    {
        return $this->render('PreparadoresBundle::renuncia_preparador.html.twig');
    }
}
