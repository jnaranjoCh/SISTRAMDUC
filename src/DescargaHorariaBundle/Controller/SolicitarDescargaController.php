<?php

namespace DescargaHorariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SolicitarDescargaController extends Controller
{
    public function infoAction()
    {
         return $this->render('DescargaHorariaBundle:Informacion:info.html.twig');
    }
    
}
