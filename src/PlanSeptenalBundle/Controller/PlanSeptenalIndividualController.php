<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class PlanSeptenalIndividualController extends Controller
{
    /**
     * @Route("/plan-septenal/individual", name="plan-septenal-individual")
     * @Method({"GET"})
     */
    public function showAction()
    {
        return $this->render('PlanSeptenalBundle::individual.html.twig');
    }

    /**
     * @Route("/plan-septenal/individual", name="create-plan-septenal-individual")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $inicio = $request->get('inicio');
        $fin = $request->get('fin');

        $new_plan = new PlanSeptenalIndividual($inicio, $fin);

        $tramites = $request->get('tramites');

        foreach ($tramites as $tramite) {
            $new_plan->addTramite(
                new TramitePlanSeptenal([
                    'tipo' => $tramite['tipo'],
                    'periodo' => new MonthlyDateRange(
                        $tramite['periodo']['start'],
                        $tramite['periodo']['end']
                    )
                ])
            );
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($new_plan);
        $entityManager->flush();

        return new Response('', 200);
    }
}
