<?php

namespace ConcursoOposicionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/concursoOposicion/apertura_concurso_oposicion_index", name="apertura_concurso_oposicion_index")
     */
    public function aperturaDeConcursoAction()
    {
        return $this->render('ConcursoOposicionBundle::apertura_concurso.html.twig');
    }

    /**
     * @Route("/concursoOposicion/jurado", name="jurado")
     */
    public function juradoAction()
    {
        return $this->render('ConcursoOposicionBundle::jurado.html.twig');
    }

    /**
     * @Route("/concursoOposicion/cpec", name="cpec")
     */
    public function cpecAction()
    {
        return $this->render('ConcursoOposicionBundle::cpec.html.twig');
    }

    /**
     * @Route("/concursoOposicion/tablaBasicaConcurso", name="tablaBasicaConcurso")
     */
    public function concursoAction()
    {
        return $this->render('ConcursoOposicionBundle::listarConcurso.html.twig');
    }

    /**
     * @Route("/concursoOposicion/registro_usuario_oposicion", name="registro_usuario_oposicion")
     */
    public function registroAction()
    {
        return $this->render('ConcursoOposicionBundle::registroAspirante.html.twig');
    }

    /**
     * @Route("/concursoOposicion/documentacion_oposicion", name="documentacion_oposicion")
     */
    public function documentacionAction()
    {
        return $this->render('ConcursoOposicionBundle::documentacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/recusacion_oposicion", name="recusacion_oposicion")
     */
    public function recusacionAction()
    {
        return $this->render('ConcursoOposicionBundle::recusacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listaAspirantes", name="listaAspirantes")
     */
    public function listaAspirantesAction()
    {
        return $this->render('ConcursoOposicionBundle::listaAspirantes.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listaRecusacion", name="listaRecusacion")
     */
    public function listaRecusacionAction()
    {
        return $this->render('ConcursoOposicionBundle::listaRecusacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/suplentesJurado", name="suplentesJurado")
     */
    public function suplentesJuradoAction()
    {
        return $this->render('ConcursoOposicionBundle::suplentesJurado.html.twig');
    }

    /**
     * @Route("/concursoOposicion/suplentesCPEC", name="suplentesCPEC")
     */
    public function suplentesCPECAction()
    {
        return $this->render('ConcursoOposicionBundle::suplentesCPEC.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarJurados", name="listarJurados")
     */
    public function listarJuradosAction()
    {
        return $this->render('ConcursoOposicionBundle::listarJurados.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarSuplentes", name="listarSuplentes")
     */
    public function listarSuplentesAction()
    {
        return $this->render('ConcursoOposicionBundle::listarSuplentes.html.twig');
    }

    /**
     * @Route("/concursoOposicion/pruebas", name="pruebas")
     */
    public function pruebasAction()
    {
        return $this->render('ConcursoOposicionBundle::pruebas.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarPruebas", name="listarPruebas")
     */
    public function listarPruebasAction()
    {
        return $this->render('ConcursoOposicionBundle::listarPruebas.html.twig');
    }

    /**
     * @Route("/concursoOposicion/resultados", name="resultados")
     */
    public function resultadosAction()
    {
        return $this->render('ConcursoOposicionBundle::resultados.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarResultados", name="listarResultados")
     */
    public function listarResultadosAction()
    {
        return $this->render('ConcursoOposicionBundle::listarResultados.html.twig');
    }
}
