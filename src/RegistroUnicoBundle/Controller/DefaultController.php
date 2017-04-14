<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

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
    
    /*public function guardarDatosAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            return new JsonResponse("if");
        }
        else
            return new JsonResponse("else");
    }*/
    
    public function enviarEmailsAjaxAction(Request $request)
    {
        return new JsonResponse($this->getEmails($this->getAll("AppBundle:","Usuario")));
    }   
    
    public function registrarUsuarioAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $roles[] = new Rol();
            $i = 0;
            $usuario = new Usuario();
            $usuario = $this->initialiceUser($usuario);
            $usuario->setCedula($request->get("Cedula"));
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($usuario,$request->get("Password"));
            $usuario->setPassword($encoded);
            foreach($request->get("Roles") as $rol)
            {
              $roles[$i] = $this->getByName("AppBundle:","Rol",$rol);
              $i++;
            }
            $usuario->addRoles($roles);
            $usuario->setActivo(1);
            $usuario->setCorreo($request->get("Email"));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            return new JsonResponse("insertado");
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
                 return new JsonResponse("N");
            }else
            {
                return new JsonResponse("S");
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function buscarEmailAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontrado = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));

            if (!$encontrado) {
                return new JsonResponse(0);
            }else
                return new JsonResponse($encontrado->getActivo());
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

    public function obtenerIdAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $value = $this->getDoctrine()
                          ->getManager()
                          ->createQuery('SELECT MAX(r.id) AS lastId FROM RegistroUnicoBundle:Registro r')
                          ->getResult();
            return new JsonResponse($value);
        }
        else
             throw $this->createNotFoundException('Error al obtener los datos');
    }
    
    private function getEmails($object)
    {
        $i = 0;
        $datas=null;
        $data["Email"]="";
        $data["Estatus"]="";
        foreach($object as $value)
        {
           $data["Email"] = $value->getCorreo();
           if($value->getActivo())
               $data["Estatus"]="Activo";
           else
               $data["Estatus"]="Inactivo";
           $datas[$i] = $data;
           $i++;
        }
        
        return array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $datas
        );
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
        $usuario->setDireccion("");
        $usuario->setEstatusId(0);
        $usuario->setTelefono(0);
        $usuario->setRif(0);
        
        return $usuario;
    }

    private function getOneCedula($bundle,$entidad,$cedula)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCedula($cedula);
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
    
    private function getRolName($bundle,$entidad,$id)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneById($id);
    }
    
}