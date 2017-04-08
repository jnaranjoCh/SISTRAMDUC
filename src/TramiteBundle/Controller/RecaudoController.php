<?php

namespace TramiteBundle\Controller;

use TramiteBundle\Entity\Recaudo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Recaudo controller.
 *
 * @Route("recaudo")
 */
class RecaudoController extends Controller
{
    /**
     * Lists all recaudo entities.
     *
     * @Route("/", name="recaudo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recaudos = $em->getRepository('TramiteBundle:Recaudo')->findAll();

        return $this->render('recaudo/index.html.twig', array(
            'recaudos' => $recaudos,
        ));
    }

    /**
     * Creates a new recaudo entity.
     *
     * @Route("/new", name="recaudo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $recaudo = new Recaudo();
        $form = $this->createForm('TramiteBundle\Form\RecaudoType', $recaudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recaudo);
            $em->flush($recaudo);

            return $this->redirectToRoute('recaudo_show', array('id' => $recaudo->getId()));
        }

        return $this->render('recaudo/new.html.twig', array(
            'recaudo' => $recaudo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a recaudo entity.
     *
     * @Route("/{id}", name="recaudo_show")
     * @Method("GET")
     */
    public function showAction(Recaudo $recaudo)
    {
        $deleteForm = $this->createDeleteForm($recaudo);

        return $this->render('recaudo/show.html.twig', array(
            'recaudo' => $recaudo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing recaudo entity.
     *
     * @Route("/{id}/edit", name="recaudo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recaudo $recaudo)
    {
        $deleteForm = $this->createDeleteForm($recaudo);
        $editForm = $this->createForm('TramiteBundle\Form\RecaudoType', $recaudo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recaudo_edit', array('id' => $recaudo->getId()));
        }

        return $this->render('recaudo/edit.html.twig', array(
            'recaudo' => $recaudo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a recaudo entity.
     *
     * @Route("/{id}", name="recaudo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Recaudo $recaudo)
    {
        $form = $this->createDeleteForm($recaudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recaudo);
            $em->flush();
        }

        return $this->redirectToRoute('recaudo_index');
    }

    /**
     * Creates a form to delete a recaudo entity.
     *
     * @param Recaudo $recaudo The recaudo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recaudo $recaudo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recaudo_delete', array('id' => $recaudo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
