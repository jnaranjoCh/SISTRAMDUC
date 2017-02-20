<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/plan-septenal/individual", name="plan-septenal-individual")
     */
    public function mostrarPlanSeptenalIndividual()
    {
        return $this->render('PlanSeptenalBundle::individual.html.twig');
    }

    /**
     * @Route("/plan-septenal/colectivo", name="plan-septenal-colectivo")
     */
    public function mostrarPlanSeptenalcollectivo()
    {
        return $this->render('PlanSeptenalBundle::colectivo.html.twig');
    }
}
