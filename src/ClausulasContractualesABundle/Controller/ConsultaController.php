<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsultaController extends Controller
{
    
    public function consultarAction()
    {
        return $this->render('ClausulasContractualesABundle:Consulta:consultar.html.twig');
    }
    
    public function consultarBecaAction()
    {
        
        $hijos = $this->getDoctrine()->getEntityManager()->getRepository('ClausulasContractualesBundle:Hijo')->findAll();
        return $this->render('ClausulasContractualesABundle:Consulta:consultar_beca.html.twig', array('hijos' => $hijos));
    }
    
    public function consultarPrimaHijosAction()
    {
        return $this->render('ClausulasContractualesABundle:Consulta:consultar_prima_hijos.html.twig');
    }
    
    public function consultarDiscapacidadAction()
    {
        return $this->render('ClausulasContractualesABundle:Consulta:consultar_discapacidad.html.twig');
    }
    
    // public function consultaBecaAjaxAction(Request $request)
    // {
    //     $data = $this->getDoctrine()
    //                   ->getManager()
    //                   ->createQuery('SELECT u.primerNombre, u.primerApellido, h.primerNombre, h.primerApellido, h.fechaNacimiento
    //                                  FROM AppBundle:Usuario u, ClausulasContractualesBundle:Hijo h
                                     
    //                                  WHERE u.cedula = h.cedulaMadre or u.cedula = h.cedulaPadre')
    //                   ->setParameter('email',$request->get('email'))
    //                   ->getResult();
        
    //     if($data != null)        
    //         $data = $data[0]->getRegistrosParticipantes($request->get('assets'));
    //     else 
    //     {
    //         $data = new stdClass;
    //         $data->data = null;
    //         $data->num = 0;
    //     }
        
    //     return new JsonResponse( array(
    //         "draw"            => 1,
	   //     "recordsTotal"    => $data->num,
	   //     "recordsFiltered" => $data->num,
	   //     "data"            => $data->data 
    //     ));
    // }
}
