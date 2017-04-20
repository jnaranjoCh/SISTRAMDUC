<?php

namespace PlanSeptenalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;

class PlanSeptenalColectivoController extends Controller
{
    /**
     * @Route("/plan-septenal/colectivo", name="plan-septenal-colectivo-creation-view")
     */
    public function getCreationViewAction()
    {
        $next_year = (new \DateTime())->modify("+1 year")->format("Y");

        return $this->render("PlanSeptenalBundle::colectivo.html.twig", compact("next_year"));
    }

    /**
     * @Route("/plan-septenal-colectivo", name="get-plan-septenal-colectivo")
     * @Method({"GET"})
     */
    public function getAction(Request $request)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $plan_septenal_colectivo_repo = $this->get("plan_septenal.plan_septenal_colectivo_repository");

        $plan_septenal_colectivo = $plan_septenal_colectivo_repo
            ->findOneBy([
                "departamento" => $this->getUser()->getDepartamento()->getId(),
                "inicio" => $request->get("inicio")
            ]);

        if (is_null($plan_septenal_colectivo)) {
            return $this->json(["El plan septenal colectivo solicitado no existe."], 404);
        }

        return $this->json($plan_septenal_colectivo->toArray());
    }

    /**
     * @Route("/plan-septenal-colectivo/start-creation", name="start-creation-process")
     * @Method({"POST"})
     */
    public function startCreationAction(Request $request)
    {
        $inicio = $request->request->get("inicio");
        $deadline = \DateTime::createFromFormat("d/m/Y", $request->request->get("creation_deadline"));

        $plan = new PlanSeptenalColectivo($inicio, $this->getUser(), $deadline);

        $entity_manager = $this->getDoctrine()->getManager();
        $entity_manager->persist($plan);
        $entity_manager->flush();

        return $this->json("success");
    }
}
