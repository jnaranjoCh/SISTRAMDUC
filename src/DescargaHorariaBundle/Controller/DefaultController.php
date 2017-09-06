<?php

namespace DescargaHorariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DescargaHorariaBundle:Default:index.html.twig');
    }
}
