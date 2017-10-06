<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use \stdClass;

class MantenimientoMontosController extends Controller
{
    public function mantenimientoDeMontosIndexAction()
    {
        return $this->render('ClausulasContractualesABundle:MantenimientoMontos:mantenimeinto_montos.html.twig');
    }
    
    public function mantenimientoDeMontosEnviarDataAction()
    {
        
        $montoData = new stdClass;
        $data[] = [];
        $i = 0;
        
        $em = $this->getDoctrine()->getManager();
        $montos = $em->getRepository('TramiteBundle:Monto')
                   ->findAll();
        $montoData->data = null;
        $montoData->num = 0;
        
        
        foreach($montos as $monto)
        {
            $data[$i]['Id'] = $monto->getId();
            $data[$i]['Descripcion'] = $monto->getDescription();
            $data[$i]['Monto'] = $monto->getAmount();
            $i++;
        }
        
        $montoData->data = $data;
        $montoData->num = $i;
        
        return new JsonResponse(array(
            "draw"            => 1,
	        "recordsTotal"    => $montoData->num,
	        "recordsFiltered" => $montoData->num,
	        "data"            => $montoData->data 
        ));
    }
}
