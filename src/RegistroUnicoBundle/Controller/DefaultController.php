<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    
    public function conultarRegistroAction()
    {
        return $this->render('RegistroUnicoBundle:Default:consultar_registro.html.twig');
    }
}
