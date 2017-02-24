<?php

namespace PreparadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PreparadoresBundle:Default:index.html.twig');
    }
}
