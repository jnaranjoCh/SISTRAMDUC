<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TramiteBundle\Entity\Recaudo;

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
    
    public function becaAction($email, $state)
    {
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCorreo($email);
        $hijos = $user->getHijosObject();
        $tipos = $this->getDoctrine()->getEntityManager()->getRepository('TramiteBundle:Duracion')->findAll();
        return $this->render('ClausulasContractualesABundle:Solicitud:beca.html.twig', array('hijos' => $hijos, 'tipos' => $tipos));
    }
    
    
    public function guardarArchivosAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $newRecaudo = new Recaudo();
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCorreo($request->get('email'));
        $duracion = $em->getRepository('TramiteBundle:Duracion')->findOneByValor($request->get('selectedDuracion')); 
        
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/estudio/';
        $dir_subida = $dir_subida."constancia_estudio_".$request->get('selectedHijo').".pdf";
        
        $newRecaudo->setDuracion($duracion);
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Constancia de estudio o de inscripción');
        $newRecaudo->setName("constancia_estudio_".$request->get('selectedHijo').".pdf");
        $newRecaudo->setFechaVencimiento(null);
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Hijo");
        $newRecaudo->setPath($dir_subida);
        if($duracion->getDescripcion() == "Otro"){
            $newRecaudo->setDuracionAdministrador($request->get('valorOtro'));
        }else{
            $newRecaudo->setDuracionAdministrador($duracion->getValor());
        }
        $em->persist($newRecaudo);
        $em->flush();

        

        if(move_uploaded_file($_FILES['input']['tmp_name'][0], $dir_subida)) {
            return new RedirectResponse($this->generateUrl('clausulas_contractuales_beca',array('email' => $request->get('email'), 'state' => 'success')));
        }else{
            return new RedirectResponse($this->generateUrl('clausulas_contractuales_beca',array('email' => $request->get('email'), 'state' => 'error')));
        }
    }

}
