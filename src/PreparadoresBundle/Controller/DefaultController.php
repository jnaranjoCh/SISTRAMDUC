<?php

namespace PreparadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ConcursosBundle\Entity\Concurso;

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
    
    /**
     * @Route("/preparadores/registrar_solicitud", name="registrar_solicitud_ajax")
     */
    public function registrarSolicitudAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $concurso = new Concurso();
            $concurso = $this->initialiceConcurso($concurso);
            $concurso->setAreaPostulacion($request->get("Asignatura"));
            $concurso->setNroVacantes($request->get("Plazas"));
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($concurso);
            $em->flush();
            return new JsonResponse("insertado");
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    private function initialiceConcurso($concurso)
    {
        $concurso->setFechaInicio("");
        $concurso->setNroVacantes(0);
        $concurso->setAreaPostulacion("");
        $concurso->setFechaPresentacion("");
        $concurso->setFechaRecepDoc("");
        
        return $concurso;
    }
}
