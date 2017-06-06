<?php

namespace ComisionRemuneradaBundle\Controller;

use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use Proxies\__CG__\AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Hackzilla\BarcodeBundle\Utility\Barcode;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Estado;
use Symfony\Component\HttpFoundation\Request;
use TramiteBundle\Entity\TipoDocumento;
use TramiteBundle\Entity\Documento;

class DefaultController extends Controller
{
    /**
     * @Route("/solicitud_serv_remun", name="solicitud_serv_remun")
     */
    public function solicitud_serv_remunAction()
    {
        return $this->render('ComisionRemuneradaBundle:Catedra:posibleRespuesta.html.twig');
    }
    /********************************/
    /*       ÁREA DE PROFESOR       */
    /********************************/

    /**
     * @Route("/comision-servicio/informacion", name="comision-servicio-informacion")
     */
    public function comisionServicioInfoAction()
    {
        return $this->render('ComisionRemuneradaBundle:Profesor:informacion.html.twig');
    }
    
    /**
     * @Route("/comision-de-servicio/estado-solicitud", name="estado-solicitud")
     */
    public function estado_sol_profAction()
    {
        $user = $this->getUser();

        $tramite = $this->getDoctrine()
                        ->getManager()
                        ->createQuery('SELECT MAX(r.id) FROM ComisionRemuneradaBundle:SolicitudComisionServicio r WHERE r.usuario_id = :user ')
                        ->setParameter('user', $user)
                        ->getOneOrNullResult();

        $tramite_actual = $this->getDoctrine()
                               ->getManager()
                               ->getRepository(SolicitudComisionServicio::class)
                               ->findBy(["id" => $tramite]);
        
        return $this->render('ComisionRemuneradaBundle:Profesor:estado_sol_prof.html.twig',
            array('tramite_actual' => $tramite_actual));
    }

    /********************************/
    /*        ÁREA DE INFORME       */
    /********************************/

    /**
     * @Route("/comision-servicio/informe", name="comision-servicio-informe")
     */
    public function informeAction()
    {
        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservcio:informeComision.html.twig');
    }
    /**
     * @Route("/comision-servicio/codigo-de-barra", name="comision-servicio-codigo-de-barra")
     */
    public function barcodeImageAction($code = '000000000001')
    {
        $barcode = $this->get('hackzilla_barcode');
        $barcode->setMode(Barcode::MODE_PNG);

        $headers = array(
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="'.$code.'.png"'
        );

        return new Response($barcode->outputImage($code), 200, $headers);
    }
    /**
     * @Route("/comision-servicio/informe-pdf", name="comision-servicio-informe-pdf")
     */
    public function informePDFAction()
    {
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('ComisionRemuneradaBundle:solicitudcomisionservcio:informeComision-print.html.twig', array(
            //..Send some data to your view if you need to //
        ));

        $filename = 'InformePDF-ComisionServicio';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }

    /***********************************/
    /* ÁREA DE CONSEJO DE DEPARTAMENTO */
    /***********************************/

    /**
     * @Route("/comision-de-servicio/solicitudes", name="comision_servicio_solicitudes")
     */
    public function solicitudesComisionServicioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(SolicitudComisionServicio::class);
        $tramites_comision = $tramites->findAll();
        return $this->render('ComisionRemuneradaBundle:ConsejoDepartamento:solicitudes_comision_servicio.html.twig',
            array('tramites_comision' => $tramites_comision));
    }

    /**
     * @Route("/comision-de-servicio/solicitud/{id}", name="comision_servicio_ver_solicitud")
     */    
    public function verSolicitudAction(SolicitudComisionServicio $tramite)
    {
        $recaudos = $tramite->getRecaudos();
        
        return $this->render('ComisionRemuneradaBundle:ConsejoDepartamento:ver_solicitud.html.twig',
            array('tramite' => $tramite, 'recaudos' => $recaudos));
    }

    /**
     * @Route("/comision-de-servicio/insertar", name="comision-de-servicio-insertar")
     * @Method("POST")
     */
    public function insertarAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $transicionRepo = $em->getRepository(Transicion::class);
            $transicion = $transicionRepo->findOneBy(["tramite" => $request->get("Solicitud")]);

            $estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => $request->get("Estatus")]);

            $transicion->setEstado($estado);
            $transicion->setFecha(new \DateTime("now"));
            $transicion->setDoc_info($request->get("Motivo"));

            $em->persist($transicion);
            $em->flush();

            return new JsonResponse("S");
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos de inserción');
    }

    /********************************/
    /*      ÁREA DE CATEDRA         */
    /********************************/
    /**
     * @Route("/comision-de-servicio/ver-solicitudes", name="comision_servicio_solicitudes_catedra")
     */
    public function verSolicitudesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(SolicitudComisionServicio::class);
        $tramites_comision = $tramites->findAll();
        return $this->render('ComisionRemuneradaBundle:Catedra:verSolicitudes.html.twig',
            array('tramites_comision' => $tramites_comision));
    }

    /**
     * @Route("/comision-de-servicio/ver-solicitud/{id}", name="comision_servicio_revisar_solicitud")
     */
    public function revisarSolicitudAction(SolicitudComisionServicio $tramite)
    {
        $recaudos = $tramite->getRecaudos();

        return $this->render('ComisionRemuneradaBundle:Catedra:posibleRespuesta.html.twig',
            array('tramite' => $tramite, 'recaudos' => $recaudos));
    }

    /**
     * @Route("/comision-de-servicio/insertar-posible-respuesta", name="comision-de-servicio-insertar-posible-respuesta")
     * @Method("POST")
     */
    public function insertarPosibleRespuestaAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {            
            $em = $this->getDoctrine()->getManager();
            $transicionRepo = $em->getRepository(Transicion::class);
            $transicion = $transicionRepo->findOneBy(["id" => $request->get("solicitud")]);
            
            $solicitudRepo = $em->getRepository(SolicitudComisionServicio::class);
            $solicitud = $solicitudRepo->findOneBy(["id" => $request->get("solicitud")]);

            $estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => "4"]);

            $transicion->setEstado($estado);
            $transicion->setFecha(new \DateTime("now"));

            $solicitud->setPosibleRespuesta($request->get("respuesta"));

            $em->persist($transicion);
            $em->flush();

            $em->persist($solicitud);
            $em->flush();

            return new JsonResponse("S");
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos de inserción');
    }

    /*******************************/
    /* ÁREA DE CONSEJO DE FACULTAD */
    /*******************************/

    /**
     * @Route("/comision-servicio/solicitudes-atendidas-facultad", name="comision_servicio_atendidas_facultad")
     */
    public function atendidasFacultadAction()
    {
        return $this->render('ComisionRemunerada:solicitudcomisionservicio:tramAtendFacultad.html.twig');
    }
    
    /**
     * @Route("/comision-de-servicio/consejo-de-facultad/solicitudes", name="comision_servicio_solicitudes_facultad")
     */
    public function verSolicitudesFacultadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(SolicitudComisionServicio::class);
        $tramites_comision = $tramites->findAll();
        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:verSolicitudesFacultad.html.twig',
            array('tramites_comision' => $tramites_comision));
    }

    /**
     * @Route("/comision-de-servicio/consejo-de-facultad/ver-solicitud/{id}", name="comision_servicio_ver_solicitud_facultad")
     */
    public function verSolicitudFacultadAction(SolicitudComisionServicio $tramite)
    {
        $recaudos = $tramite->getRecaudos();

        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:verSolicitudFacultad.html.twig',
            array('tramite' => $tramite, 'recaudos' => $recaudos));
    }

    /**
     * @Route("/comision-de-servicio/insertar-facultad", name="comision_servicio_insertar_facultad")
     * @Method("POST")
     */
    public function insertarConsejoFacultadAction(Request $request) {

        if($request->isXmlHttpRequest()){

            //Entity Manager
            $em = $this->getDoctrine()->getManager();

            $transicionRepo = $em->getRepository(Transicion::class);
            $transicion = $transicionRepo->findOneBy(["tramite" => $request->get("Solicitud")]);

            $estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => $request->get("Estatus")]);

            $transicion->setEstadoConsejo($estado);
            $transicion->setEstado($estado);
            $transicion->setFechaConsejo(new \DateTime("now"));
            $transicion->setMotivoConsejo($request->get("Motivo"));

            //Persistimos en el objeto
            $em->persist($transicion);

            //Insertarmos en la base de datos
            $em->flush();

            return new JsonResponse("S");
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos de inserción');

    }

    /**
     * @Route("/comision-servicio/informe-consejo-facultad", name="informe-consejo-facultad")
     * @Method({"GET", "POST"})
     */
    public function informeFacultadAction(Request $request)
    {
        return $this->render('ComisionRemuneradaBundle:solicitudcomisionservicio:informeComision.html.twig',
            array('tramite' => $request->get("Solicitud")));
    }

    /**
     * @Route("/comision-servicio/llenarPDF", name="facultad-llenarPDF")
     * @Method({"GET", "POST"})
     */
    public function llenarPDFAction(Request $request) {

        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getManager();
            // $tramiteJubilacion = new TramiteJubilacion();
            $tramiteComision =  $em->getRepository(SolicitudComisionServicio::class);
            $numTramite = $tramiteComision->findOneBy(["id" => $request->get("Tramite")]); //Probar el request metido entre las comillas. $request->get("Tramite")


            $tipo_documento_repo = $em->getRepository(TipoDocumento::class);
            $tipo_documento = $tipo_documento_repo->findOneBy(["id" => $request->get("TipoDocumento")]);


            $documento = new Documento();
            $documento
                ->asignarDocA($numTramite);
            $documento->setTipoDocumento($tipo_documento);
            $documento->setAsunto($request->get("Asunto"));
            $documento->setActa($request->get("Acta"));
            $documento->setFecha(new \DateTime("now"));
            $documento->setNum($request->get("Numero"));
            $documento->setContenido($request->get("Contenido"));


            //Persistimos en el objeto
            $em->persist($documento);

            //Insertarmos en la base de datos
            $em->flush();

            $respuesta[] = "";
            $respuesta['numDoc'] = $documento->getId();
            $respuesta['alerta'] = "S";

            return new JsonResponse($respuesta);
        }
        else{
            throw $this->createNotFoundException('Error al solicitar datos de inserción');
        }

        //return $this->redirectToRoute('jubilacion-informe-jubilacion-pdf');
    }

    /********************************/
    /* ÁREA DE ASUNTOS PROFESORALES */
    /********************************/

    /**
     * @Route("/comision-de-servicio/solicitudes", name="comision_servicio_solicitudes_aapp")
     */
    public function verSolicitudesAAPPAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(SolicitudComisionServicio::class);
        $tramites_comision = $tramites->findAll();
        return $this->render('ComisionRemuneradaBundle:AsuntosProfesorales:verSolicitudesAAPP.html.twig',
            array('tramites_comision' => $tramites_comision));
    }
}