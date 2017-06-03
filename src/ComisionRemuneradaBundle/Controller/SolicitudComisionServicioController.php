<?php

namespace ComisionRemuneradaBundle\Controller;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use TramiteBundle\Entity\TipoRecaudo;
use TramiteBundle\Entity\TipoTramite;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Estado;
use TramiteBundle\Entity\Recaudo;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Response;


/**
 * Solicitudcomisionservicio controller.
 *
 * @Route("solicitudcomisionservicio")
 */
class SolicitudComisionServicioController extends Controller
{
    /**
     * Creates a new solicitudComisionServicio entity.
     *
     * @Route("/new", name="solicitudcomisionservicio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        return $this->render('ComisionRemuneradaBundle:SolicitudComisionServicio:new.html.twig');
    }

    /**
     * @Route("/solicitud/guardar-datos", name="solicitud_guardar_ajax")
     * @Method("POST")
     */
    public function guardarDatosAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            return new JsonResponse("Datos guardados");
        }
        else
            return new JsonResponse("Error");
    }

    /**
     * @Route("/solicitud/guardar-archivos-datos", name="solicitud_guardararchivos_ajax")
     * @Method("POST")
     */
    public function guardarArchivosAjaxAction(Request $request)
    {
        //return new Response();
        $band1=0;
        $band2=0;
        $em = $this->getDoctrine()->getManager();
        $solicitudComisionServicio = new Solicitudcomisionservicio();
        $transicion = new Transicion();
        
        $user = $em->getRepository(Usuario::class)
            ->findOneByCedula($_POST['gemail']);

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

        $solicitudComisionServicio
            ->assignTo($user)// Se asigna un usuario a la solicitud
            ->setTipoTramite($tipo_tramite);  // Se modifica el tipo de tramite

        $solicitudComisionServicio
            ->setFechaRecibido(new \DateTime("now")); // Se asigna la fecha del sistema a la solicitud

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/oficioSolicitud/users/';
        $fichero_subido = $dir_subida."oficioSolicitud_".$user->getId().".pdf";

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("oficioSolicitud_".$user->getId().".pdf");
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo1);
        $em->persist($newRecaudo);

        $solicitudComisionServicio->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input1']['name'][0], $fichero_subido))        
            $band1 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cartaDesignacion/users/';
        $fichero_subido = $dir_subida."cartaDesignacion_".$user->getId().".pdf";

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("cartaDesignacion_".$user->getId().".pdf");
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo2);
        $em->persist($newRecaudo);

        $solicitudComisionServicio->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input2']['name'][0], $fichero_subido))
            $band2 = 1;
        
        $transicion
            ->asignarA($solicitudComisionServicio)// Se asigna una transicion a la solicitud
            ->setEstado($estado);                         // Se cambia el estado de la transición

        $transicion->setFecha(new \DateTime("now"));      // Se asigna la fecha del sistema a la solicitud

        $em->persist($solicitudComisionServicio);

        foreach ($solicitudComisionServicio->getRecaudos() as $actualRecaudo) {
            $actualRecaudo->setTramite($solicitudComisionServicio);
        }

        $em->flush();
        
        if($band1 == 1 and $band2 == 1) {
            return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'success')));
        }else{
            return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'error')));
        }
    }    

    /**
     * @Route("/solicitud/buscar-cedula", name="solicitud_buscarcedula_ajax")
     * @Method("POST")
     */
    public function buscarCedulaAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $data['Primera_vez'] = "N";
            $data['Solicitar'] = "S";
            $data['Existe'] = "S";
            $em = $this->getDoctrine()->getManager();
            $usuario_id = $em->createQuery('SELECT u.id FROM AppBundle:Usuario u WHERE u.cedula = :ci')
                            ->setParameter('ci', $request->get("Cedula"))
                            ->getOneOrNullResult();

            $usuario = $em->getRepository(Usuario::class)
                ->findOneBy(["id" => $usuario_id]);

            if (!$usuario){ //no existe usuario con esa cedula
                $data['Existe'] = "N";
            }else{
                $solicitud_MaxId = $em->createQuery('SELECT MAX(r.id) FROM ComisionRemuneradaBundle:SolicitudComisionServicio r WHERE r.usuario_id = :user ')
                    ->setParameter('user', $usuario)
                    ->getOneOrNullResult();

                $data['Existe'] = "S";
                if (!$solicitud_MaxId){ //No existe una solicitud para ese usuario, es primera vez
                    $data['Primera_vez'] = "S";
                }else{
                    $solicitud = $em->getRepository(SolicitudComisionServicio::class)
                        ->findOneBy(["id" => $solicitud_MaxId]);

                    if ($solicitud){
                        if (!$solicitud->getTransicion()->getFecha() or ($solicitud->getTransicion()->getEstado() == ("Pendiente"|"Enviada"))){
                            $data['Solicitar'] = "N";
                        }
                        if ($solicitud->getTransicion()->getFecha()){
                            $now = new \DateTime("now");
                            $date = $solicitud->getTransicion()->getFecha();
                            $interval = $now->diff($date);
                            if ( ($solicitud->getTransicion()->getEstado() == "Aprobada") and ( $interval->y < 1 ) ){
                                $data['Solicitar'] = "N";
                            }
                        }
                    }
                }
            }
            return new JsonResponse($data);
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos');
    }
    
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
     * @Route("/newSave", name="solicitudcomisionservicio_newsave")
     * @Method({"GET", "POST"})
     */
    public function newSaveAction(Request $request)
    {
        return new Response($_FILES['input1']['name'][0]);
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
