<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

class ConsultarDatosController extends Controller
{
    
    public function consultarRegistroAction()
    {
        return $this->render('RegistroUnicoBundle:ConsultarDatos:consultar_registro.html.twig');
    }
    
    public function enviarEmailsAjaxAction(Request $request)
    {
        return new JsonResponse($this->getEmails($this->getAll("AppBundle:","Usuario")));
    }   
    
    public function enviarRegistrosDeUsuarioAjaxAction(Request $request)
    {
        $data = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT u,r FROM AppBundle:Usuario u JOIN u.registros r WHERE u.correo = :email')
                      ->setParameter('email',$request->get('email'))
                      ->getResult()[0]->getRegistros();
        
        return new JsonResponse( array(
            "draw"            => 1,
	        "recordsTotal"    => $data->num,
	        "recordsFiltered" => $data->num,
	        "data"            => $data->data 
        ));
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
    
    private function getEmails($object)
    {
        $i = 0;
        $datas=null;
        $data["Email"]="";
        $data["Estatus"]="";
        foreach($object as $value)
        {
            if($value->getIsRegister())
            {
               $data["Email"] = $value->getCorreo();
               if($value->getActivo())
                   $data["Estatus"]="Activo";
               else
                   $data["Estatus"]="Inactivo";
               $datas[$i] = $data;
               $i++;
            }
        }
        
        return array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $datas
        );
    }

    private function getAll($bundle,$entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findAll();
    }
    
    private function getOneEmail($bundle,$entidad,$email)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($email);
    }
    
}