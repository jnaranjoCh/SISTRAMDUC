<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PlanSeptenalColectivoController extends Controller
{
   /**
     * @Route("/plan-septenal/colectivo", name="plan-septenal-colectivo")
     */
    public function showAction()
    {
        return $this->render('PlanSeptenalBundle::colectivo.html.twig');
    }
}
