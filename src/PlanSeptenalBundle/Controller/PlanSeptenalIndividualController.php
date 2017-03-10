<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;

class PlanSeptenalIndividualController extends Controller
{
    /**
     * @Route("/plan-septenal/individual", name="display-plan-septenal-individual")
     * @Method({"GET"})
     */
    public function displayAction()
    {
        return $this->render('PlanSeptenalBundle::individual.html.twig');
    }

    /**
     * @Route("/plan-septenal-individual", name="get-plan-septenal-individual")
     * @Method({"GET"})
     */
    public function getAction()
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['owner' => $this->getUser()->getId()]);

        $response = is_null($plan_septenal_individual) ? null : $plan_septenal_individual->toArray();

        return $this->json($response);
    }

    /**
     * @Route("/plan-septenal-individual", name="create-plan-septenal-individual")
     * @Method({"POST"})
     */
    public function createOrUpdateAction(Request $request)
    {
        $inicio = $request->request->get('inicio');
        $fin = $request->request->get('fin');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'fin' => $fin, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            $plan_septenal_individual = new PlanSeptenalIndividual($inicio, $fin);
            $plan_septenal_individual->assignTo($this->getUser());
        } else {
            $persisted_tramites = $plan_septenal_individual->getTramites();
            foreach ($persisted_tramites as $tramite) {
                $entity_manager->remove($tramite);
            }
            $persisted_tramites->clear();
        }

        $plan_septenal_individual->addTramites($request->get('tramites'));

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return new Response('', 200);
    }
}
