<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use AppBundle\Entity\Usuario;

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
    
    public function registrarUsuarioAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $usuario = new Usuario();
            $usuario = $this->initialiceUser($usuario);
            $usuario->setCorreo($_POST["Email"]);
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($usuario,$_POST["Password"]);
            $usuario->setPassword($encoded);
            $usuario->setRolId($this->getByName("AppBundle:","Rol",$_POST["TipoUsuario"])->getId());
            $usuario->setActivo(1);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            return new JsonResponse($usuario->getCorreo()." ".$usuario->getPassword()." ".$usuario->getRolId()."   insertado");
        }
        else
            return new JsonResponse("else");
    }
    
    public function buscarEmailAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontrado = $this->getOneEmail("AppBundle:","Usuario",$_POST["Email"]);

            if (!$encontrado) {
                 return new JsonResponse("N");
            }else
            {
                return new JsonResponse("S");
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function enviarDataAjaxAction(Request $request)
    {
        $val[][] = "";
        if($request->isXmlHttpRequest())
        {
            $estatus = $this->getAll("RegistroUnicoBundle:","Estatus");
            $nivel = $this->getAll("RegistroUnicoBundle:","Nivel");
            $tipo_regitro = $this->getAll("RegistroUnicoBundle:","TipoRegistro");
            $cargo = $this->getAll("RegistroUnicoBundle:","Cargo");
            
            $rol = $this->getAll("AppBundle:","Rol");

            if (!$estatus || !$nivel || !$tipo_regitro || !$cargo || !$rol) {
                 throw $this->createNotFoundException('Error al obtener datos iniciales');
            }else
            {
                $val = $this->bdToArrayDescription($estatus,'estatus',$val);
                $val = $this->bdToArrayDescription($nivel,'nivel',$val);
                $val = $this->bdToArrayDescription($tipo_regitro,'tipo_registro',$val);
                $val = $this->bdToArrayDescription($cargo,'cargo',$val);
                $val = $this->bdToArrayNombre($rol,'rol',$val);
                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    private function bdToArrayDescription($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getDescription();
           $i++;
        }
        return $val;
    }
    
    private function bdToArrayNombre($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getNombre();
           $i++;
        }
        return $val;
    }

    private function initialiceUser($usuario)
    {
        $usuario->setCedula("");
        $usuario->setPrimerNombre("");
        $usuario->setSegundoNombre("");
        $usuario->setPrimerApellido("");
        $usuario->setSegundoApellido("");
        $usuario->setNacionalidad("");
        $usuario->setCorreo("");
        $usuario->setTelefono(0);
        $usuario->setRif(0);
        
        return $usuario;
    }

    private function getOneEmail($bundle,$entidad,$email)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($email);
    }
    
    private function getByName($bundle,$entidad,$name)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByNombre($name);
    }
    
    private function getAll($bundle,$entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findAll();
    }
    
    
}
