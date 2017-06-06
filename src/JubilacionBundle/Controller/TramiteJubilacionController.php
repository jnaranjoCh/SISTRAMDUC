<?php

namespace JubilacionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JubilacionBundle\Entity\TramiteJubilacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TramiteBundle\Entity\TipoTramite;
use TramiteBundle\Entity\TipoRecaudo;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Estado;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TramiteBundle\Entity\Recaudo;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tramitejubilacion controller.
 *
 * @Route("tramitejubilacion")
 */
class TramiteJubilacionController extends Controller
{
    /**
     * Lists all tramiteJubilacion entities.
     *
     * @Route("/", name="tramitejubilacion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tramiteJubilacions = $em->getRepository('JubilacionBundle:TramiteJubilacion')->findAll();

        /*return $this->render('tramitejubilacion/index.html.twig', array(
            'tramiteJubilacions' => $tramiteJubilacions,
        ));*/
        return $this->redirectToRoute("tramitejubilacion_new");
    }

    /**
     * Creates a new tramiteJubilacion entity.
     *
     * @Route("/new/{apr}", name="tramitejubilacion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($apr = "initial")
    {
        return $this->render('JubilacionBundle:TramiteJubilacion:new.html.twig');

        /*$tramiteJubilacion = new TramiteJubilacion();
        $transicion = new Transicion();
        $form = $this->createForm('JubilacionBundle\Form\TramiteJubilacionType', $tramiteJubilacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /* Se obtienen todos los registros de la tabla tipo_tramite y se busca el registro que 
            corresponda a Jubilacion */
           // $tipo_tramite_repo = $em->getRepository(TipoTramite::class);
            //$tipo_tramite = $tipo_tramite_repo->findOneBy(["id" => 7]);

            /* Se obtienen todos los registros de la tabla tipo_recaudo y se extraen los requeridos de
            acuerdo al tramite correspondiente*/
           /* $tipo_recaudo1_repo = $em->getRepository(TipoRecaudo::class);
            $tipo_recaudo1 = $tipo_recaudo1_repo->findOneBy(["id" => 6]);
            $tipo_recaudo2 = $tipo_recaudo1_repo->findOneBy(["id" => 7]);
            $tipo_recaudo3 = $tipo_recaudo1_repo->findOneBy(["id" => 8]);
            $tipo_recaudo4 = $tipo_recaudo1_repo->findOneBy(["id" => 9]);
            $tipo_recaudo5 = $tipo_recaudo1_repo->findOneBy(["id" => 10]);

             /* Se obtienen los datos de la tabla estado y se extraen los requeridos (solicitudes enviadas en estatus pendiente) */
            /*$estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => 1]);

            /* Se le asigna a cada recaudo su tipo*/
           /* $i = 1;
            foreach ($tramiteJubilacion->getRecaudos() as $actualRecaudo) {
                if ($i == 1){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo1);
                }
                if ($i == 2){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo2);
                }
                if ($i == 3){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo3);
                }
                if ($i == 4){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo4);
                }
                if ($i == 5){
                    $actualRecaudo->setTipoRecaudo($tipo_recaudo5);
                }
                $i+=1;
                if ($i == 6){
                    break;
                }
            }

            /* Se asigna el usuario y el tipo de trámite a la tabla tramite*/
           /* $tramiteJubilacion
                ->assignTo($this->getUser()->getId()->getId())
                ->setTipoTramite($tipo_tramite);

            /* Se asigna la fecha en que se realiza la solicitud al tramite*/
           /* $tramiteJubilacion->setfecha_recibido(new \DateTime("now"));

            $transicion
                ->asignarA($tramiteJubilacion) // Se asigna una transicion a la solicitud
                ->setEstado($estado);                  // Se cambia el estado de la transición

            $transicion->setEstadoConsejo($estado);

            $transicion->setFecha(new \DateTime("now"));
            
            $em->persist($tramiteJubilacion);

            /* Se asigna el trámite cada recaudo*/
            /*foreach ($tramiteJubilacion->getRecaudos() as $actualRecaudo) {
                $actualRecaudo->setTramite($tramiteJubilacion);
            }

            /* Guardamos en Base de Datos*/
            //$em->flush();

            /* Luego de enviarse la solucitud se direcciona a la vista de enviado*/
           /* return $this->redirectToRoute('jubilacion-estado-solicitud', array('id' => $tramiteJubilacion->getId()));
        }

        /* De lo contrario se mantiene en la misma vista*/
       /* return $this->render('JubilacionBundle:tramitejubilacion:new.html.twig', array(
            'tramiteJubilacion' => $tramiteJubilacion,
            'form' => $form->createView(),
        ));*/
    }

    /**
     * @Route("/solicitud/guardar-archivos", name="solicitud_guardararchivos")
     * @Method({"GET", "POST"})
     */
    public function guardarArchivosAjaxAction(Request $request)
    {
        //Return new response($_FILES['input3']['name'][0]);
        $band1=0;
        $band2=0;
        $em = $this->getDoctrine()->getManager();
        $tramiteJubilacion = new TramiteJubilacion();
        $transicion = new Transicion();


        /* Se obtienen todos los registros de la tabla tipo_tramite y se busca el registro que
            corresponda a Jubilacion */
        $tipo_tramite_repo = $em->getRepository(TipoTramite::class);
        $tipo_tramite = $tipo_tramite_repo->findOneBy(["id" => 7]);

        /* Se obtienen todos los registros de la tabla tipo_recaudo y se extraen los requeridos de
            acuerdo al tramite correspondiente*/
        $tipo_recaudo_repo = $em->getRepository(TipoRecaudo::class);
        $tipo_recaudo1 = $tipo_recaudo_repo->findOneBy(["id" => 6]);
        $tipo_recaudo2 = $tipo_recaudo_repo->findOneBy(["id" => 7]);
        $tipo_recaudo3 = $tipo_recaudo_repo->findOneBy(["id" => 8]);
        $tipo_recaudo4 = $tipo_recaudo_repo->findOneBy(["id" => 9]);
        $tipo_recaudo5 = $tipo_recaudo_repo->findOneBy(["id" => 10]);

        /* Se obtienen los datos de la tabla estado y se extraen los requeridos (solicitudes enviadas en estatus pendiente) */
        $estado_repo = $em->getRepository(Estado::class);
        $estado = $estado_repo->findOneBy(["id" => 1]);


        $tramiteJubilacion
            ->assignTo($this->getUser())// Se asigna un usuario a la solicitud
            ->setTipoTramite($tipo_tramite);  // Se modifica el tipo de tramite

        $tramiteJubilacion
            ->setfecha_recibido(new \DateTime("now")); // Se asigna la fecha del sistema a la solicitud

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/oficioSolicitud'.$this->getUser()->getId().'/';
        $fichero_subido = $dir_subida."oficioSolicitud_".$this->getUser()->getId().".pdf";

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("oficioSolicitud".$this->getUser()->getId().".pdf");
        $newRecaudo->setUsuario($this->getUser());
        $newRecaudo->setTipoRecaudo($tipo_recaudo1);
        $em->persist($newRecaudo);

        $tramiteJubilacion->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input1']['tmp_name'][0], $dir_subida))
            $band1 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/constanciaAAPP'.$this->getUser()->getId().'/';

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("constanciaAAPP".$this->getUser()->getId().".pdf");
        $newRecaudo->setUsuario($this->getUser());
        $newRecaudo->setTipoRecaudo($tipo_recaudo2);
        $em->persist($newRecaudo);

        $tramiteJubilacion->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input2']['tmp_name'][0], $dir_subida))
            $band2 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/reciboPago'.$this->getUser()->getId().'/';

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("reciboPago".$this->getUser()->getId().".pdf");
        $newRecaudo->setUsuario($this->getUser());
        $newRecaudo->setTipoRecaudo($tipo_recaudo3);
        $em->persist($newRecaudo);

        $tramiteJubilacion->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input3']['tmp_name'][0], $dir_subida))
            $band2 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/antecedentesServicios'.$this->getUser()->getId().'/';

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("antecedentesServicios".$this->getUser()->getId().".pdf");
        $newRecaudo->setUsuario($this->getUser());
        $newRecaudo->setTipoRecaudo($tipo_recaudo4);
        $em->persist($newRecaudo);

        $tramiteJubilacion->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input4']['tmp_name'][0], $dir_subida))
            $band2 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/constanciaPreparador'.$this->getUser()->getId().'/';

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("constanciaPreparador".$this->getUser()->getId().".pdf");
        $newRecaudo->setUsuario($this->getUser());
        $newRecaudo->setTipoRecaudo($tipo_recaudo5);
        $em->persist($newRecaudo);

        $tramiteJubilacion->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input5']['tmp_name'][0], $dir_subida))
            $band2 = 1;

        $transicion
            ->asignarA($tramiteJubilacion)// Se asigna una transicion a la solicitud
            ->setEstado($estado);                         // Se cambia el estado de la transición

        $transicion->setEstadoConsejo($estado);

        $transicion->setFecha(new \DateTime("now"));      // Se asigna la fecha del sistema a la solicitud

        $em->persist($tramiteJubilacion);

        foreach ($tramiteJubilacion->getRecaudos() as $actualRecaudo) {
            $actualRecaudo->setTramite($tramiteJubilacion);
        }

        $em->flush();

        if($band1 == 1 and $band2 == 1) {
            return new RedirectResponse($this->generateUrl('tramitejubilacion_new',array('apr' => 'success')));
        }else{
            return new RedirectResponse($this->generateUrl('tramitejubilacion_new',array('apr' => 'error')));
        }
    }

    /**
     * Finds and displays a tramiteJubilacion entity.
     *
     * @Route("/{id}", name="tramitejubilacion_show")
     * @Method("GET")
     */
    public function showAction(TramiteJubilacion $tramiteJubilacion)
    {
        $deleteForm = $this->createDeleteForm($tramiteJubilacion);

        return $this->render('JubilacionBundle:tramitejubilacion:show.html.twig', array(
            'tramiteJubilacion' => $tramiteJubilacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tramiteJubilacion entity.
     *
     * @Route("/{id}/edit", name="tramitejubilacion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TramiteJubilacion $tramiteJubilacion)
    {
        $deleteForm = $this->createDeleteForm($tramiteJubilacion);
        $editForm = $this->createForm('JubilacionBundle\Form\TramiteJubilacionType', $tramiteJubilacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tramitejubilacion_edit', array('id' => $tramiteJubilacion->getId()));
        }

        return $this->render('tramitejubilacion/edit.html.twig', array(
            'tramiteJubilacion' => $tramiteJubilacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tramiteJubilacion entity.
     *
     * @Route("/{id}", name="tramitejubilacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TramiteJubilacion $tramiteJubilacion)
    {
        $form = $this->createDeleteForm($tramiteJubilacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tramiteJubilacion);
            $em->flush();
        }

        return $this->redirectToRoute('tramitejubilacion_index');
    }

    /**
     * Creates a form to delete a tramiteJubilacion entity.
     *
     * @param TramiteJubilacion $tramiteJubilacion The tramiteJubilacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TramiteJubilacion $tramiteJubilacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tramitejubilacion_delete', array('id' => $tramiteJubilacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
