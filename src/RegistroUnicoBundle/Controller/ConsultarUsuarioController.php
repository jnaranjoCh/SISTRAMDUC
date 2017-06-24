<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use \stdClass;

class ConsultarUsuarioController extends Controller
{
    
    public function consultarUsuarioAction()
    {
        return $this->render('RegistroUnicoBundle:ConsultarUsuario:consultar_usuario.html.twig');
    }

    public function buscarEmailAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontrado = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));

            if (!$encontrado) {
                return new JsonResponse(0);
            }else
                return new JsonResponse(!$encontrado->getIsRegister());
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    public function obtenerDatosDeUsuarioAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $data = new stdClass;
            $user = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));
            $data->Cedula = $user->getCedula();
            $data->Id = $user->getId();
            return new JsonResponse($data);
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    public function obtenerRolesDeUsuarioAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $data = new stdClass;
            $user = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));
            $roles = $user->getRoles();
            $i = 0;
            
            foreach($roles as $rol)
            {
                $data->data[$i]["Rol"] = $rol;
                $i++;
            }

            return new JsonResponse( array(
                "draw"            => 1,
                "recordsTotal"    => $i,
                "recordsFiltered" => $i,
                "data"            => $data->data 
            ));
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    public function buscarCedulaAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontradoCedula = $this->getOneCedula("AppBundle:","Usuario",$request->get("Cedula"));
            $encontradoEmail = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));

            if (($encontradoEmail == null && $encontradoCedula == null) || ($encontradoEmail == null && $encontradoCedula->getId() == $request->get("Id")) || ($encontradoCedula == null && $encontradoEmail->getId() == $request->get("Id")) || ($encontradoCedula->getId() == $request->get("Id") && $encontradoEmail->getId() == $request->get("Id"))) {
                 return new JsonResponse("N");
            }else
            {
                return new JsonResponse("S");
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    public function actualizarDatosDeUsuarioAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("AppBundle:Usuario")->findOneByCorreo($request->get("EmailActual"));
            $user->setCedula($request->get("Cedula"));
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user,$request->get("Password"));
            $user->setPassword($encoded);
            $i = 0;
            foreach($request->get("Roles") as $rol)
            {
              $roles[$i] = $this->getByName("AppBundle:","Rol",$rol);
              $i++;
            }
            $user->setRoles($roles);
            $user->setCorreo($request->get("Email"));
            $em->flush();
            return new JsonResponse("actualizado");
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    private function getOneEmail($bundle,$entidad,$email)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($email);
    }

    private function getOneCedula($bundle,$entidad,$cedula)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCedula($cedula);
    }

    private function getByName($bundle,$entidad,$name)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByNombre($name);
    }
}