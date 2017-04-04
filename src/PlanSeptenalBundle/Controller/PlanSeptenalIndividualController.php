<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;

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
    public function getAction(Request $request)
    {
        $inicio = $request->get('inicio');
        $fin = $request->get('fin');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'fin' => $fin, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe."], 404);
        }

        return $this->json($plan_septenal_individual->toArray(), 200);
    }

    /**
     * @Route("/plan-septenal-individual", name="create-plan-septenal-individual")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $inicio = $request->request->get('inicio');
        $fin = $request->request->get('fin');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_colectivo_repo = $entity_manager->getRepository(PlanSeptenalColectivo::class);

        $plan_septenal_colectivo = $plan_septenal_colectivo_repo
            ->findOneBy(["inicio" => $inicio, "fin" => $fin]);

        if (is_null($plan_septenal_colectivo)) {
            return $this->json(["Error. El plan colectivo correspondiente no existe"], 400);
        }

        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'fin' => $fin, 'owner' => $this->getUser()->getId()]);

        if (! is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual ya existe"], 400);
        }

        $plan_septenal_individual = new PlanSeptenalIndividual($inicio, $fin);
        $plan_septenal_individual
            ->assignTo($this->getUser())
            ->addTramites($request->get('tramites'))
            ->setPlanSeptenalColectivo($plan_septenal_colectivo);

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json(['success'], 200);
    }

    /**
     * @Route("/plan-septenal-individual", name="update-plan-septenal-individual")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request)
    {
        $inicio = $request->request->get('inicio');
        $fin = $request->request->get('fin');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'fin' => $fin, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe."], 404);
        }

        $persisted_tramites = $plan_septenal_individual->getTramites();
        foreach ($persisted_tramites as $tramite) {
            $entity_manager->remove($tramite);
        }
        $persisted_tramites->clear();

        $plan_septenal_individual->addTramites($request->get('tramites'));

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json('success', 200);
    }

    /**
     * @Route("/plan-septenal-individual/ask-for-approval", name="ask-for-approval-plan-septenal-individual")
     * @Method({"PUT"})
     */
    public function askForApprovalAction(Request $request)
    {
        $inicio = $request->request->get('inicio');
        $fin = $request->request->get('fin');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $entity_manager->getRepository(PlanSeptenalIndividual::class);

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'fin' => $fin, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe."], 404);
        }

        $plan_septenal_individual->askForApproval();

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json('success', 200);
    }
}
