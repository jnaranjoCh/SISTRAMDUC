<?php
 
namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use RegistroUnicoBundle\Entity\UsuarioFechaCargo;
use ClausulasContractualesBundle\Entity\Hijo;
use RegistroUnicoBundle\Entity\Revista;
use RegistroUnicoBundle\Entity\Participante;
use RegistroUnicoBundle\Entity\Registro;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use RegistroUnicoBundle\Entity\Cargo;
use TramiteBundle\Entity\Recaudo;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

class EliminarUsuarioController extends Controller
{

    public function eliminarUsuarioIndexAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    public function activarUsuarioIndexAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    public function eliminarEnviarUsuarioAction(Request $request)
    {
        $usersNames = [];
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('AppBundle:Usuario')
                      ->findAll();
        $i = 0;
        foreach($users as $user)
        {
            if($user->getActivo())
            {
                $usersNames[$i] = $user->getCorreo();
                $i++;
            }
        }
        return new JsonResponse($usersNames);
    }
    
    public function activarEnviarUsuarioAction(Request $request)
    {
        $usersNames = [];
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('AppBundle:Usuario')
                      ->findAll();
        $i = 0;
        foreach($users as $user)
        {
            if(!$user->getActivo())
            {
                $usersNames[$i] = $user->getCorreo();
                $i++;
            }
        }
        return new JsonResponse($usersNames);
    }
    
    public function eliminarUsuarioAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        { 
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Usuario')
                          ->findOneByCorreo($request->get('user'));
            $user->setActivo(0);
            $em->flush();
            return new JsonResponse("usuario desactivado");
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function activarUsuarioAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        { 
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Usuario')
                          ->findOneByCorreo($request->get('user'));
            $user->setActivo(1);
            $em->flush();
            return new JsonResponse("usuario activado");
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
}