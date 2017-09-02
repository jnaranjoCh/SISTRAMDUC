<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SolicitudController extends Controller
{
    public function primaHijosAction()
    {
        return $this->render('ClausulasContractualesABundle:Solicitud:prima_hijos.html.twig');
    }
    
    public function discapacidadAction()
    {
        return $this->render('ClausulasContractualesABundle:Solicitud:discapacidad.html.twig');
    }
    
    public function becaAction()
    {
        return $this->render('ClausulasContractualesABundle:Solicitud:beca.html.twig');
    }
    
    public function guardarArchivosAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $countIdHijo = $this->getDoctrine()
                               ->getManager()
                               ->createQuery('SELECT MAX(r.id) AS lastId FROM TramiteBundle:Recaudo r')
                               ->getResult();
        $aux = ($countIdHijo[0]['lastId'] - $countIdCountHijo)+1;
        $recaudo = $em->getRepository('TramiteBundle:Recaudo')
                     ->findOneById($aux);
                     
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/constancia_estudio_inscripcion/hijos/';
        $fichero_subido = $dir_subida.$recaudo->getName();
        $arr = explode("_",$recaudo->getName());
        $arr = $arr[count($arr)-1];
        $arr = explode(".",$arr);
        $recaudo->setPath($fichero_subido);
        $em->flush();
        move_uploaded_file($_FILES['input']['tmp_name'][0], $fichero_subido);
    }

}
