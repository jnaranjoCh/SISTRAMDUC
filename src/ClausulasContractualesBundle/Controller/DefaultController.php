<?php

namespace ClausulasContractualesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function defaultAction()
    {
        return $this->render('ClausulasContractualesBundle:Default:default.html.twig');
    }
}
