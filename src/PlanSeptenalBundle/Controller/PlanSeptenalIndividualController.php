<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Utils\Helpers;

class PlanSeptenalIndividualController extends Controller
{
    /**
     * @Route("/plan-septenal/individual", name="plan-septenal-individual-creation-view")
     * @Method({"GET"})
     */
    public function getCreacionViewAction()
    {
        return $this->render('PlanSeptenalBundle::individual.html.twig');
    }

    /**
     * @Route("/plan-septenal-individual/get-all", name="get-all-plan-septenal-individual")
     * @Method({"GET"})
     */
    public function getAllAction(Request $request)
    {
        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");

        $inicio = $request->query->get('inicio');

        $planes = $plan_septenal_individual_repo->findBy(['inicio' => $inicio]);

        $resp = [];
        foreach ($planes as $plan) {
            $resp[] = [ $plan->getId(), $plan->getOwnerName(), $plan->getTramitesCount(), $plan->getStatus() ];
        }

        return $this->json(['data' => $resp]);
    }

    /**
     * @Route("/plan-septenal-individual", name="get-plan-septenal-individual")
     * @Method({"GET"})
     */
    public function getAction(Request $request)
    {
        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");

        $criteria = Helpers::filterKeys($request->query->all(), ['id', 'inicio']);

        if (! isset($criteria['id'])) {
            $criteria['owner'] = $this->getUser()->getId();
        }

        $plan_septenal_individual = $plan_septenal_individual_repo->findOneBy($criteria);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe"], 404);
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

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_colectivo_repo = $this->get("plan_septenal.plan_septenal_colectivo_repository");

        $plan_septenal_colectivo = $plan_septenal_colectivo_repo
            ->findOneBy(["inicio" => $inicio]);

        if (is_null($plan_septenal_colectivo)) {
            return $this->json(["Error. El plan colectivo correspondiente no existe"], 400);
        }

        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");

        $user = $this->getUser();

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'owner' => $user->getId()]);

        if (! is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual ya existe"], 400);
        }

        $plan_septenal_individual = new PlanSeptenalIndividual($inicio, $user, $plan_septenal_colectivo);
        $plan_septenal_individual->addTramites($request->get('tramites'));

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json('success', 200);
    }

    /**
     * @Route("/plan-septenal-individual", name="update-plan-septenal-individual")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request)
    {
        $inicio = $request->request->get('inicio');

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe"], 404);
        }

        $persisted_tramites = $plan_septenal_individual->getTramites();
        foreach ($persisted_tramites as $tramite) {
            $entity_manager->remove($tramite);
        }
        $persisted_tramites->clear();

        $plan_septenal_individual->addTramites($request->request->get('tramites'));

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

        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");

        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(['inicio' => $inicio, 'owner' => $this->getUser()->getId()]);

        if (is_null($plan_septenal_individual)) {
            return $this->json(["El plan septenal individual no existe"], 404);
        }

        $plan_septenal_individual->askForApproval();

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json('success', 200);
    }

    /**
     * @Route("/plan-septenal-individual/approve", name="approve-plan-septenal-individual")
     * @Method({"POST"})
     */
    public function approveAction(Request $request)
    {
        $entity_manager = $this->getDoctrine()->getManager();

        $plan_septenal_individual_repo = $this->get("plan_septenal.plan_septenal_individual_repository");
        $plan_septenal_individual = $plan_septenal_individual_repo
            ->findOneBy(["id" => $request->get('id')]);

        if (is_null($plan_septenal_individual)) {
            return $this->json('Plan septenal individual no existe', 404);
        }

        if ($plan_septenal_individual->getStatus() != 'Esperando aprobación') {
            return $this->json('Plan debe estar en espera por aprobación', 400);
        }

        $plan_septenal_individual->approve();

        $entity_manager->persist($plan_septenal_individual);
        $entity_manager->flush();

        return $this->json('success', 200);
    }
}
