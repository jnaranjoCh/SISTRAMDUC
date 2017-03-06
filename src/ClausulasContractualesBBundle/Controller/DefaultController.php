<?php

namespace ClausulasContractualesBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ClausulasContractualesBBundle:Default:index.html.twig');
    }
}
