<?php

namespace DescargaHorariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CargosController extends Controller
{
    public function consultarCargosAction()
    {
         return $this->render('DescargaHorariaBundle:AdmCargos:consultar_cargos.html.twig');
    }
    
    
}
