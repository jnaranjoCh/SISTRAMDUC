<?php

namespace ComisionRemuneradaBundle\Controller;

use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Form\FormError;

/**
 * Solicitudcomisionservicio controller.
 *
 * @Route("solicitudcomisionservicio")
 */
class SolicitudComisionServicioController extends Controller
{
    /**
     * Lists all solicitudComisionServicio entities.
     *
     * @Route("/", name="solicitudcomisionservicio_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $solicitudComisionServicios = $em->getRepository('ComisionRemuneradaBundle:SolicitudComisionServicio')->findAll();
        return $this->redirectToRoute('solicitudcomisionservicio_new');
        /*return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:index.html.twig', array(
            'solicitudComisionServicios' => $solicitudComisionServicios,
        ));*/
    }

    /**
     * Creates a new solicitudComisionServicio entity.
     *
     * @Route("/new", name="solicitudcomisionservicio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $solicitudComisionServicio = new Solicitudcomisionservicio();
        // Se crea el formulario
        $form = $this->createForm('ComisionRemuneradaBundle\Form\SolicitudComisionServicioType', $solicitudComisionServicio);
        $form->handleRequest($request);

        //Validacion de Archios
        /*$notBlankConstraint = new NotBlankConstraint();
        $notBlankConstraint->message = 'Por favor, debe cargar un archivo PDF.';*/
        //Para cada capitulo validamos de ser asi agregamos el error
        /*foreach ($solicitudComisionServicio->getRecaudos() as $key => $recaudo) {
            $errors = $this->get('validator')->validateValue(
                $solicitudComisionServicio->getRecaudos()->get($key)->getFile(),
                $notBlankConstraint );
            foreach ($errors as $error) {
                $form->get('recaudos')->addError( new FormError("Para el Recaudo ".($key + 1)." , debe cargar un archivo PDF."));
            }
        }*/

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solicitudComisionServicio);
            $em->flush();
            /*foreach ($solicitudComisionServicio->getRecaudos() as $actualRecaudo) {
                $actualRecaudo->setSolicitudComisionServicio($solicitudComisionServicio);
            }*/

            return $this->redirectToRoute('solicitudcomisionservicio_show', array('id' => $solicitudComisionServicio->getId()));
        }

        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:new.html.twig', array(
            'solicitudComisionServicio' => $solicitudComisionServicio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a solicitudComisionServicio entity.
     *
     * @Route("/{id}", name="solicitudcomisionservicio_show")
     * @Method("GET")
     */
    public function showAction(SolicitudComisionServicio $solicitudComisionServicio)
    {
        $deleteForm = $this->createDeleteForm($solicitudComisionServicio);

        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:show.html.twig', array(
            'solicitudComisionServicio' => $solicitudComisionServicio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing solicitudComisionServicio entity.
     *
     * @Route("/{id}/edit", name="solicitudcomisionservicio_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SolicitudComisionServicio $solicitudComisionServicio)
    {
        $deleteForm = $this->createDeleteForm($solicitudComisionServicio);
        $editForm = $this->createForm('ComisionRemuneradaBundle\Form\SolicitudComisionServicioType', $solicitudComisionServicio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitudcomisionservicio_edit', array('id' => $solicitudComisionServicio->getId()));
        }

        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:edit.html.twig', array(
            'solicitudComisionServicio' => $solicitudComisionServicio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a solicitudComisionServicio entity.
     *
     * @Route("/{id}", name="solicitudcomisionservicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SolicitudComisionServicio $solicitudComisionServicio)
    {
        $form = $this->createDeleteForm($solicitudComisionServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($solicitudComisionServicio);
            $em->flush();
        }

        return $this->redirectToRoute('solicitudcomisionservicio_index');
    }

    /**
     * Creates a form to delete a solicitudComisionServicio entity.
     *
     * @param SolicitudComisionServicio $solicitudComisionServicio The solicitudComisionServicio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SolicitudComisionServicio $solicitudComisionServicio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solicitudcomisionservicio_delete', array('id' => $solicitudComisionServicio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
