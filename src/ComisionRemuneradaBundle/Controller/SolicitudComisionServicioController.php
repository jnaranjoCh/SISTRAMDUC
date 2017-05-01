<?php

namespace ComisionRemuneradaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use TramiteBundle\Entity\TipoRecaudo;
use TramiteBundle\Entity\TipoTramite;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Estado;
use ComisionRemuneradaBundle\Form\SolicitudComisionServicioType;

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
        return $this->redirectToRoute('solicitudcomisionservicio_new');
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
        $transicion = new Transicion();

        // Se crea el formulario
        $form = $this->createForm('ComisionRemuneradaBundle\Form\SolicitudComisionServicioType', $solicitudComisionServicio);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /* Se obtienen todos los registros de la tabla tipo_tramite y se busca el registro que 
            corresponda a Comision */
            $tipo_tramite_repo = $em->getRepository(TipoTramite::class);
            $tipo_tramite = $tipo_tramite_repo->findOneBy(["id" => 6]);
            
            /* Se obtienen todos los registros de la tabla tipo_recaudo y se extrae los requeridos de 
            acuerdo al tramite correspondiente*/
            $tipo_recaudo_repo = $em->getRepository(TipoRecaudo::class);
            $tipo_recaudo1 = $tipo_recaudo_repo->findOneBy(["id" => 4]);
            $tipo_recaudo2 = $tipo_recaudo_repo->findOneBy(["id" => 5]);
            
            /* Se obtienen los datos de la tabla estado y se exttrae el requerido (enviada) */
            $estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => 1]);

            /* Se le asigna a cada recaudo su tipo*/
            $i = 1;
            foreach ($solicitudComisionServicio->getRecaudos() as $actualRecaudo) {
                if ($i == 1){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo1);
                }
                if ($i == 2){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo2);
                }
                $i+=1;
                if ($i == 3){
                    break;
                }
            }
            
            $solicitudComisionServicio
                ->assignTo($this->getUser())     // Se asigna un usuario a la solicitud
                ->setTipoTramite($tipo_tramite); // Se modifica el tipo de tramite

            $transicion
                ->asignarA($solicitudComisionServicio)        // Se asigna una transicion a la solicitud
                ->setEstado($estado);                         // Se cambia el estado de la transición
            
            $transicion->setFecha(new \DateTime("now"));      // Se asigna la fecha del sistema a la solicitud

            $em->persist($solicitudComisionServicio);

            foreach ($solicitudComisionServicio->getRecaudos() as $actualRecaudo) {
                $actualRecaudo->setTramite($solicitudComisionServicio);
            }

            $em->flush();
            
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
