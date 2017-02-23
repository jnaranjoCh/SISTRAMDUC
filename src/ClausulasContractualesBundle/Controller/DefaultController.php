<?php

namespace ClausulasContractualesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function primaHijosAction()
    {
        return $this->render('ClausulasContractualesBundle:Default:prima_hijos.html.twig');
    }
}
