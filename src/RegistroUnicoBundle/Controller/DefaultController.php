<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;

class DefaultController extends Controller
{
    public function registrarUsuarioAction()
    {
        return $this->render('RegistroUnicoBundle:Default:registrar_usuario.html.twig');
    }
    
    
    public function registrarDatosUsuarioAction()
    {
        return $this->render('RegistroUnicoBundle:Default:registrar_datos.html.twig');
    }
    
    public function consultarRegistroAction()
    {
        return $this->render('RegistroUnicoBundle:Default:consultar_registro.html.twig');
    }
    
    public function guardarDatosAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            return new JsonResponse("if");
        }
        else
            return new JsonResponse("else");
    }
    
    public function enviarDataAction(Request $request)
    {
        $val[][] = "";
        if($request->isXmlHttpRequest())
        {
            $estatus = $this->getAll("Estatus");
            $nivel = $this->getAll("Nivel");
            $tipo_regitro = $this->getAll("TipoRegistro");
            $cargo = $this->getAll("Cargo");
            
            if (!$estatus || !$nivel || !$tipo_regitro || !$cargo) {
                 throw $this->createNotFoundException('Error al obtener datos iniciales');
            }else
            {
                $val = $this->bdToArray($estatus,'estatus',$val);
                $val = $this->bdToArray($nivel,'nivel',$val);
                $val = $this->bdToArray($tipo_regitro,'tipo_registro',$val);
                $val = $this->bdToArray($cargo,'cargo',$val);
                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    private function bdToArray($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getDescription();
           $i++;
        }
        return $val;
    }
    
    private function getAll($entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RegistroUnicoBundle:'.$entidad)
                    ->findAll();
    }
    
}
