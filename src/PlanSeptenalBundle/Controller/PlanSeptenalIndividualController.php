<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PlanSeptenalIndividualController extends Controller
{
    /**
     * @Route("/plan-septenal/individual", name="plan-septenal-individual")
     */
    public function mostrarPlanSeptenalIndividual()
    {
        return $this->render('PlanSeptenalBundle::individual.html.twig');
    }
}
