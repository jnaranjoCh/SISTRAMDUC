<?php

namespace ComisionRemuneradaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/comision_remunerada_info", name="comision_remunerada_info")
     */
    public function comision_remunerada_infoAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:comision_remunerada_info.html.twig');
    }
    /**
     * @Route("/solicitud_serv_remun", name="solicitud_serv_remun")
     */
    public function solicitud_serv_remunAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:solicitud_serv_remun.html.twig');
    }
    /**
     * @Route("/solicitudes_serv_remun", name="solicitudes_serv_remun")
     */
    public function solicitudes_serv_remunAction()
    {
        return $this->render('ComisionRemuneradaBundle:AAPP:solicitudes_serv_remun.html.twig');
    }
}
