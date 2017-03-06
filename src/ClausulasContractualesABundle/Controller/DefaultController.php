<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function primaHijosAction()
    {
        return $this->render('ClausulasContractualesABundle:Default:prima_hijos.html.twig');
    }
    
    public function discapacidadAction()
    {
        return $this->render('ClausulasContractualesABundle:Default:discapacidad.html.twig');
    }
    
    public function becaAction()
    {
        return $this->render('ClausulasContractualesABundle:Default:beca.html.twig');
    }

    public function consultarAction()
    {
        return $this->render('ClausulasContractualesABundle:Default:consultar.html.twig');
    }
}
