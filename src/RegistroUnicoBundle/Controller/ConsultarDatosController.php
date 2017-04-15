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
    
}