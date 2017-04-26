<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

class RegistrarUsuarioController extends Controller
{
    public function registrarUsuarioAction()
    {
        return $this->render('RegistroUnicoBundle:RegistrarUsuario:registrar_usuario.html.twig');
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
            $usuario->setIsRegister(0);
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
            $encontradoId = $this->getOneCedula("AppBundle:","Usuario",$request->get("Cedula"));
            $encontradoEmail = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));

            if (!$encontradoId && !$encontradoEmail) {
                 return new JsonResponse("N");
            }else
            {
                return new JsonResponse("S");
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
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
        $usuario->setTelefono('');
        $usuario->setRif('');
        $usuario->setEdad(0);
        $usuario->setSexo('');
        
        return $usuario;
    }

    private function getOneCedula($bundle,$entidad,$cedula)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCedula($cedula);
    }
    
    private function getOneEmail($bundle,$entidad,$cedula)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($cedula);
    }
    

    private function getByName($bundle,$entidad,$name)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByNombre($name);
    }
    
}