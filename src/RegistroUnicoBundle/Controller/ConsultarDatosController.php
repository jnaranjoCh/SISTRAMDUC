<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}