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
    public function discapacidadIndexAction($state = "initial"){
        return $this->render('ClausulasContractualesABundle:Solicitud:discapacidad.html.twig');
    }

    public function primaHijosAction($email, $state)
    {
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCorreo($email);
        $hijos = $user->getHijosObject();
        $tipos = $this->getDoctrine()->getEntityManager()->getRepository('TramiteBundle:Duracion')->findAll();
        return $this->render('ClausulasContractualesABundle:Solicitud:prima_hijos.html.twig', array('hijos' => $hijos, 'tipos' => $tipos));
    }

    public function guardarArchivosPrimaAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCorreo($request->get('email'));
        $dir_subida_carta_solteria = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/solteria/';
        $dir_subida_carta_solteria = $dir_subida_carta_solteria."carta_solteria_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Carta de soltería');
        
        $recaudo = new Recaudo();
        $recaudo->setName("carta_solteria_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_carta_solteria);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);

        $dir_subida_carta_expensas = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/expensas/';
        $dir_subida_carta_expensas = $dir_subida_carta_expensas."carta_expensas_".$request->get('selectedHijo').".pdf";

        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Carta de expensas');
        $recaudo = new Recaudo();
        $recaudo->setName("carta_expensas_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_carta_expensas);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);
        
        $dir_subida_constancia_estudio = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/estudio/';
        $dir_subida_constancia_estudio = $dir_subida_constancia_estudio."constancia_estudio_".$request->get('selectedHijo').".pdf";
        $duracion = $em->getRepository('TramiteBundle:Duracion')->findOneByValor($request->get('selectedDuracion')); 
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Constancia de estudio o de inscripción');
        
        $recaudo = new Recaudo();
        $recaudo->setName("constancia_estudio_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setDuracion($duracion);
        $recaudo->setPath($dir_subida_constancia_estudio);
        $recaudo->setDuracionAdministrador(null);
        if($duracion->getDescripcion() == "Otro"){
            $recaudo->setDuracionAdministrador($request->get('valorOtro'));
        }else{
            $recaudo->setDuracionAdministrador($duracion->getValor());
        }
        $em->persist($recaudo);
        $em->flush();

        if(move_uploaded_file($_FILES['input']['tmp_name'][0], $dir_subida_carta_solteria)) {
            if(move_uploaded_file($_FILES['input']['tmp_name'][1], $dir_subida_carta_expensas)) {
                if(move_uploaded_file($_FILES['input']['tmp_name'][2], $dir_subida_constancia_estudio)) {
                    return new RedirectResponse($this->generateUrl('clausulas_contractuales_prima_hijos',array('email' => $request->get('email'), 'state' => 'success')));
                }else{
                    return new RedirectResponse($this->generateUrl('clausulas_contractuales_prima_hijos',array('email' => $request->get('email'), 'state' => 'error')));
                }
            }else{
                return new RedirectResponse($this->generateUrl('clausulas_contractuales_prima_hijos',array('email' => $request->get('email'), 'state' => 'error')));
            }
        }else{
            return new RedirectResponse($this->generateUrl('clausulas_contractuales_prima_hijos',array('email' => $request->get('email'), 'state' => 'error')));
        }
    }
    
    public function discapacidadAction(Request $request){
        $value[][] = "";
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCedula($request->get('Cedula'));
        $hijos = $user->getHijosObject();
        $value = $this->bdToArrayDescription($hijos, $value);
        return new JsonResponse($value);
    }

    public function guardarArchivosDiscapacidadAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCedula($request->get('mail'));

        $dir_subida_carta_solteria = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/solteria/';
        $dir_subida_carta_solteria = $dir_subida_carta_solteria."carta_solteria_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Carta de soltería');
        $recaudo = new Recaudo();
        $recaudo->setName("carta_solteria_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_carta_solteria);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);

        $dir_subida_carta_expensas = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/expensas/';
        $dir_subida_carta_expensas = $dir_subida_carta_expensas."carta_expensas_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Carta de expensas');
        $recaudo = new Recaudo();
        $recaudo->setName("carta_expensas_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_carta_expensas);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);

        $dir_subida_cedula_identidad = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cedula/hijos/';
        $dir_subida_cedula_identidad = $dir_subida_cedula_identidad."cedula_identidad_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Cedula');
        
        $recaudo = new Recaudo();
        $recaudo->setName("cedula_identidad_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_cedula_identidad);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);
        
        $dir_subida_informe_medico = $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/informeMedico/';
        $dir_subida_informe_medico = $dir_subida_informe_medico."informe_medico_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Informe médico');
        
        $recaudo = new Recaudo();
        $recaudo->setName("informe_medico_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_informe_medico);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);

        
        $dir_subida_certificado_conapdis= $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/certificadoConapdis/';
        $dir_subida_certificado_conapdis = $dir_subida_certificado_conapdis."certificado_conapdis_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Certificado de personas con discapacidad CONAPDIS');
        
        $recaudo = new Recaudo();
        $recaudo->setName("certificado_conapdis_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_certificado_conapdis);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);

        $dir_subida_calificacion_conapdis= $this->container->getParameter('kernel.root_dir').'/../web/uploads/constancias/hijo/calificacionConapdis/';
        $dir_subida_calificacion_conapdis = $dir_subida_calificacion_conapdis."calificacion_conapdis_".$request->get('selectedHijo').".pdf";
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')->findOneByNombre('Calificación de personas con discapacidad CONAPDIS');
        
        $recaudo = new Recaudo();
        $recaudo->setName("calificacion_conapdis_".$request->get('selectedHijo').".pdf");
        $recaudo->setFechaVencimiento(null);
        $recaudo->setDuracion($em->getRepository('TramiteBundle:Duracion')->findOneByValor('12'));
        $recaudo->setUsuario($user);
        $recaudo->setTipoRecaudo($tipo_recaudo);
        $recaudo->setTabla("");
        $recaudo->setPath($dir_subida_calificacion_conapdis);
        $recaudo->setDuracionAdministrador(null);
        $em->persist($recaudo);
        $em->flush();

        if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][0], $dir_subida_calificacion_conapdis)) {
            if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][1], $dir_subida_certificado_conapdis)) {
                if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][2], $dir_subida_informe_medico)) {
                    if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][3], $dir_subida_cedula_identidad)) {
                        if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][4], $dir_subida_carta_solteria)) {
                            if(move_uploaded_file($_FILES['inputDiscapacidad']['tmp_name'][5], $dir_subida_carta_expensas)) {                                
                               return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'success')));
                            }else{
                                return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));    
                            }
                        }else{
                            return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));    
                        }
                    }else{
                        return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));
                    }
                }else{
                    return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));
                }
            }else{
                return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));
            }
        }else{
            return new RedirectResponse($this->generateUrl('clausulas_contractuales_discapacidad',array('email' => $request->get('email'), 'state' => 'error')));
        }
    }
    
    public function becaAction($email, $state)
    {
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Usuario')->findOneByCorreo($email);
        $hijos = $user->getHijosObject();
        $tipos = $this->getDoctrine()->getEntityManager()->getRepository('TramiteBundle:Duracion')->findAll();
        return $this->render('ClausulasContractualesABundle:Solicitud:beca.html.twig', array('hijos' => $hijos, 'tipos' => $tipos));
    }
    
    
    public function guardarArchivosBecaAjaxAction(Request $request){
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
        $newRecaudo->setTabla("");
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

    private function bdToArrayDescription($object,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val['nombre'][$i] = $value->getPrimerNombre();
           $val['apellido'][$i] = $value->getPrimerApellido();
           $val['id'][$i] = $value->getId();
           $i++;
        }
        return $val;
    }

}
