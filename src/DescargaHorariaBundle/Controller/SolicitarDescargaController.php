<?php

namespace DescargaHorariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SolicitarDescargaController extends Controller
{
    public function infoAction()
    {
         return $this->render('DescargaHorariaBundle:Informacion:info.html.twig');
    }
    
    public function solicitarAction()
    {
        return $this->render('DescargaHorariaBundle:Solicitud:solicitar.html.twig');
    }
    
    public function generarOficioSolicitudAction()
    {
        return $this->render('DescargaHorariaBundle:Solicitud:generar_oficio_solicitud.html.twig');
    }
    
    /*public function oficioSolicitudAction()
    {
        return $this->render('DescargaHorariaBundle:Informes:oficio_solicitud.html.twig');
    }*/
    
    public function oficioSolicitudAction(Request $request)
    {
        return $this->render('DescargaHorariaBundle:Informes:oficio_solicitud.html.twig');
    }
    
    public function getDatosOficioSolicitudAction(Request $request){
        
        $usuario = $this->getOneCedula("AppBundle:","Usuario",$request->get("Cedula"));
    }
   
    
    public function oficioSolicitudDataAjaxAction(Request $request)
    {
        $val[][] = "";
        if($request->isXmlHttpRequest())
        {
            /*$estatus = $this->getAll("RegistroUnicoBundle:","Estatus");
            $nivel = $this->getAll("RegistroUnicoBundle:","Nivel");
            $cargo = $this->getAll("RegistroUnicoBundle:","Cargo");*/
            $facultad = $this->getAll("RegistroUnicoBundle:","Facultad");
            

            if (!$facultad) {
                 throw $this->createNotFoundException('Error al obtener datos');
            }else
            {
                /*$val = $this->bdToArrayDescription($estatus,'estatus',$val);
                $val = $this->bdToArrayDescription($nivel,'nivel',$val);
                $val = $this->bdToArrayDescription($cargo,'cargo',$val);*/
                $val = $this->bdToArrayNombre($facultad,'facul',$val);
                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function buscarCedulaAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontrado = $this->getOneCedula("AppBundle:","Usuario",$request->get("Cedula"));

            if (!$encontrado) {
                return new JsonResponse(0);
            }else
            {
                if($encontrado->getActivo() && $encontrado->getIsRegister())
                {
                    return new JsonResponse(1);    
                }else
                    return new JsonResponse(0);
            }
                
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    
    private function getOneCedula($bundle,$entidad,$cedula)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCedula($cedula);
    }
    
    
    
    /*FALTA
    public function informeJubilacionAction(Request $request)
    {
        return $this->render('JubilacionBundle::informeJubilacion.html.twig',
            array('tramite' => $request->get("Solicitud")));
    }
    */
    
}
