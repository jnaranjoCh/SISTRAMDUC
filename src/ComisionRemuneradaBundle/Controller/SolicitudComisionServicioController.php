<?php

namespace ComisionRemuneradaBundle\Controller;

use ComisionRemuneradaBundle\ComisionRemuneradaBundle;
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
     * @Route("/new/{apr}", name="solicitudcomisionservicio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($apr = "initial")
    {
        return $this->render('ComisionRemuneradaBundle:SolicitudComisionServicio:new.html.twig');
    }

    /**
     * @Route("/solicitud/guardar-archivos-datos", name="solicitud_guardararchivos_ajax")
     * @Method({"GET", "POST"})
     */
    public function guardarArchivosAjaxAction(Request $request)
    {
        //Return new response($_FILES['input3']['name'][0]);
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
            ->setUsuario($user->getId());

        $solicitudComisionServicio
            ->setFechaRecibido(new \DateTime("now")); // Se asigna la fecha del sistema a la solicitud

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/oficioSolicitud_'.$user->getId().'/';
        $fichero_subido = $dir_subida."oficioSolicitud_".$user->getId().".pdf";

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("oficioSolicitud_".$user->getId());
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo1);
        $em->persist($newRecaudo);

        $solicitudComisionServicio->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input3']['tmp_name'][0], $dir_subida))
            $band1 = 1;

        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cartaDesignacion_'.$user->getId().'/';
        $fichero_subido = $dir_subida."cartaDesignacion_".$user->getId().".pdf";

        $newRecaudo = new Recaudo();
        $newRecaudo->setPath($dir_subida);
        $newRecaudo->setName("cartaDesignacion_".$user->getId());
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo2);
        $em->persist($newRecaudo);

        $solicitudComisionServicio->addRecaudo($newRecaudo);
        if (move_uploaded_file($_FILES['input2']['tmp_name'][0], $dir_subida))
            $band2 = 1;
        
        $transicion
            ->asignarA($solicitudComisionServicio)// Se asigna una transicion a la solicitud
            ->setEstado($estado);                         // Se cambia el estado de la transición

        $transicion->setEstadoConsejo($estado);
        $transicion->setEstadoDepartamento($estado);
        $transicion->setEstadoCatedra($estado);
        $transicion->setMotivoConsejo("Enviada al Consejo de Departamento");
        $transicion->setMotivoDepartamento("Enviada a la Catedra");
        $transicion->setMotivocatedra(" ");

        $transicion->setFechaConsejo(new \DateTime("now"));      // Se asigna la fecha del sistema a la solicitud
        $transicion->setFechaEnvDepartamento(new \DateTime("now"));

        $em->persist($solicitudComisionServicio);

        foreach ($solicitudComisionServicio->getRecaudos() as $actualRecaudo) {
            $actualRecaudo->setTramite($solicitudComisionServicio);
        }

        $em->flush();
        
        if($band1 == 1 and $band2 == 1) {
            return new RedirectResponse($this->generateUrl('solicitudcomisionservicio_new',array('apr' => 'success')));
        }else{
            return new RedirectResponse($this->generateUrl('solicitudcomisionservicio_new',array('apr' => 'error')));
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
            $solicitudRepository = $em->getRepository('ComisionRemuneradaBundle:SolicitudComisionServicio');

            /*$usuario = $em->getRepository(Usuario::class)
                ->findOneByCedula($request->get("Cedula"));*/

            $usuario_id = $em->createQuery('SELECT u.id FROM AppBundle:Usuario u WHERE u.cedula = :ci')
                            ->setParameter('ci', $request->get("Cedula"))
                            ->getOneOrNullResult();

            $usuario = $em->getRepository(Usuario::class)
                ->findOneBy(["id" => $usuario_id]);

            if (!$usuario){ //no existe usuario con esa cedula
                $data['Existe'] = "N";
            }else{
                $solicitud_MaxId = $solicitudRepository->findByUsuario($usuario_id);
                /*$solicitud_MaxId = $em->createQuery('SELECT MAX(r.id) FROM ComisionRemuneradaBundle:SolicitudComisionServicio r WHERE r.usuario_id = :user ')
                    ->setParameter('user', $usuario)
                    ->getOneOrNullResult();*/

                $data['Existe'] = "S";
                if (!$solicitud_MaxId){ //No existe una solicitud para ese usuario, es primera vez
                    $data['Primera_vez'] = "S";
                }else{
                    $id = $em->createQuery('SELECT u.id FROM ComisionRemuneradaBundle:SolicitudComisionServicio u WHERE u.id = :id')
                        ->setParameter('id', 1)
                        ->getOneOrNullResult();

                    $solicitud = $em->getRepository(SolicitudComisionServicio::class)
                        ->findOneBy(["id" => $id]);

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
}
