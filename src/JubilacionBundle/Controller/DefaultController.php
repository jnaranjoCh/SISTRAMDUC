<?php

namespace JubilacionBundle\Controller;

use Fixtures\Bundles\AnnotationsBundle\Entity\Test;
use JubilacionBundle\JubilacionBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Hackzilla\BarcodeBundle\Utility\Barcode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TramiteBundle\Entity\TipoDocumento;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Documento;
use JubilacionBundle\Entity\TramiteJubilacion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Proxies\__CG__\TramiteBundle\Entity\Estado;
use TramiteBundle\Entity\TransicionRepository;
use TramiteBundle\Entity\TransicionConsejo;

class DefaultController extends Controller
{
    /**
     * @Route("/jubilacion/informacion", name="jubilacion-informacion")
     */
    public function infoAction()
    {
        return $this->render('JubilacionBundle::info.html.twig');
    }

    /**
     * @Route("/jubilacion/solicitud", name="jubilacion-solicitud")
     */
    public function solicitudAction()
    {
        return $this->render('JubilacionBundle::solicitud.html.twig');
    }

    /**
     * @Route("/jubilacion/direccion-asuntos-Prof", name="jubilacion-direccion-asuntos-Prof")
     */
    public function direccionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(TramiteJubilacion::class)->findAll();
       // $tramites = $tramites->findAll();
        return $this->render('JubilacionBundle::dirAsuntosProfesorales.html.twig',
            array('tramites' => $tramites));
    }

    /**
     * @Route("/jubilacion/consejo-facultad", name="jubilacion-consejo-facultad")
     */
    public function consejoAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $transicionRepository = $entityManager->getRepository('TramiteBundle:Transicion');
        $tramites = $transicionRepository->getListadoAprobado();
        
        return $this->render('JubilacionBundle::consejoFacultad.html.twig',
            array('tramites' => $tramites));
        
       /* $entityManager = $this->getDoctrine()->getManager();
        
        // Se consulta en DAP todas las solicitudes que han sido aprobadas
        $transicionRepository = $entityManager->getRepository('TramiteBundle:Transicion');
        $aprobados = $transicionRepository->getListadoAprobado();

        // Se obtienen todos los tramites
        $tramiteRepository = $entityManager->getRepository(TramiteJubilacion::class);

        // Se obtiene un estado (Pendiente)
        $estado_repo = $entityManager->getRepository(Estado::class);
        $estado = $estado_repo->findOneBy(["id" => '1']);

        // Se construye un objeto con el que se almacenara en consejo las solicitudes pendientes 
        foreach ($aprobados as $transicion)
        {
            $transicionConsejo = new TransicionConsejo();

            // Se obtiene el tramite que pertenece a la transicion actual
            $tramite = $tramiteRepository->findOneBy(["id" => $transicion->getTramite()]);

            // Se reinician los valores para estatus, fecha enviada y motivo, el resto de los campos permanecen iguales
            $transicionConsejo->setTramite($tramite);
            //$transicionConsejo->setTramite($transicion->getTramite());
            $transicionConsejo->setFecha($transicion->getFecha());
            $transicionConsejo->setEstado($estado);
            $transicionConsejo->setDoc_info("Motivo");

            //Persistimos en el objeto
            $entityManager->persist($transicionConsejo);

            //Insertarmos en la base de datos
            $entityManager->flush();
        }

        // Se recuperan todos los registros que se encuentran en la tabla que contiene la informacion pertinente
        // a Consejo de Facultad (transicion_consejo).
        $tramites = $entityManager->getRepository(TransicionConsejo::class);
        $tramites = $tramites->findAll();

        return $this->render('JubilacionBundle::consejoFacultad.html.twig',
            array('tramites' => $tramites));*/
    }

    /**
     * @Route("/jubilacion/informe-jubilacion", name="jubilacion-informe-jubilacion")
     * @Method({"GET", "POST"})
     */
    public function informeJubilacionAction(Request $request)
    {
        return $this->render('JubilacionBundle::informeJubilacion.html.twig',
            array('tramite' => $request->get("Solicitud")));
    }

    /**
     * @Route("/jubilacion/informe-jubilacion-pdf", name="jubilacion-informe-jubilacion-pdf")
     * @Method("GET")
     */
    public function informePDFAction(Request $request)
    {
        $snappy = $this->get('knp_snappy.pdf');

        $em = $this->getDoctrine()->getManager();
        //$DocumentoRepo = $em->getRepository(Documento::class);
        $numDocumento = $em->getRepository(Documento::class)->findAll();
        //$numDocumento = $DocumentoRepo->findOneBy(["id" => $request->get("Documento")]);

        $html = $this->renderView('JubilacionBundle::informeJubilacion-print.html.twig',
            array(
            'documento' => $numDocumento
            ));

        $filename = 'Informe-Jubilacion';

        return new Response(
            $snappy->getOutputFromHtml($html,
                [
                    'page-width'=>1280,
                    'margin-top'=>15,
                    'margin-left'=>25,
                    'margin-right'=>25,
                ]),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );

        //$filename = 'Informe-Jubilacion';
        /*$filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/Informes-Jubilacion/'.$numDocumento[0].'/pdf/';
         return new Response(
             $this->get('knp_snappy.pdf')->generateFromHtml(
                 $this->renderView(
                     'JubilacionBundle::informeJubilacion-print.html.twig',
                     array(
                         'documento' => $numDocumento,
                         'Content-Type'          => 'application/pdf',
                         'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
                     )
                 )
             )
         );*/
    }

    /**
     * @Route("/jubilacion/estado-solicitud", name="jubilacion-estado-solicitud")
     */
    public function estadoSolicitudAction()
    {
        $user = $this->getUser();

        $tramite = $this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT MAX(r.id) FROM JubilacionBundle:TramiteJubilacion r WHERE r.usuario_id = :user ')
            ->setParameter('user', $user)
            ->getOneOrNullResult();

        $tramite_actual = $this->getDoctrine()
            ->getManager()
            ->getRepository(TramiteJubilacion::class)
            ->findBy(["id" => $tramite]);

        return $this->render('JubilacionBundle::progresoSolicitudProf.html.twig',
            array('tramite_actual' => $tramite_actual));
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

    /**
     * @Route("/jubilacion/revisar-solicitud/{id}", name="jubilacion-revisar-solicitud")
     */
    public function revisarSolicitudAction(TramiteJubilacion $tramite)
    {
        $recaudos = $tramite->getRecaudos();

        return $this->render('JubilacionBundle::revisarSolicitud.html.twig',
            array('tramite' => $tramite, 'recaudos' => $recaudos));
        
    }

    /**
     * @Route("/jubilacion/codigo-barra", name="jubilacion-codigo-barra")
     * Muestra el codigo como una imagen png
     */
    public function barcodeImageAction($code = '000045670001')
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
     * @Route("/jubilacion/insertar", name="jubilacion-insertar")
     * @Method("POST")
     */
    public function insertarAction(Request $request) {

        if($request->isXmlHttpRequest()){

            //Entity Manager
            $em = $this->getDoctrine()->getManager();

            $transicionRepo = $em->getRepository(Transicion::class);
            $transicion = $transicionRepo->findOneBy(["tramite" => $request->get("Solicitud")]);

            $estado_repo = $em->getRepository(Estado::class);
            $estado = $estado_repo->findOneBy(["id" => $request->get("Estatus")]);
            
            $transicion->setEstado($estado);
            $transicion->setFecha(new \DateTime("now"));
            $transicion->setDoc_info($request->get("Motivo"));

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
     * @Route("/jubilacion/llenarPDF", name="jubilacion-llenarPDF")
     * @Method({"GET", "POST"})
     */
    public function llenarPDFAction(Request $request) {

        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getManager();
           // $tramiteJubilacion = new TramiteJubilacion();
            $tramiteJubilacion =  $em->getRepository(TramiteJubilacion::class);
            $numTramite = $tramiteJubilacion->findOneBy(["id" => $request->get("Tramite")]); //Probar el request metido entre las comillas. $request->get("Tramite")


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

    /**
     * @Route("/jubilacion/revisar-solicitud-consejo/{id}", name="jubilacion-revisar-solicitud-consejo")
     */
    public function revisarSolicitudConsejoAction(TramiteJubilacion $tramite)
    {
        $recaudos = $tramite->getRecaudos();

        return $this->render('JubilacionBundle::revisarSolicitudConsejo.html.twig',
            array('tramite' => $tramite, 'recaudos' => $recaudos));
    }

    /**
     * @Route("/jubilacion/insertar-consejo", name="jubilacion-insertar-consejo")
     * @Method("POST")
     */
    public function insertarConsejoAction(Request $request) {

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
     * @Route("/jubilacion/informe-jubilacion-consejo", name="jubilacion-informe-jubilacion-consejo")
     * @Method({"GET", "POST"})
     */
    public function informeConsejoAction(Request $request)
    {
        return $this->render('JubilacionBundle::informeConsejo.html.twig',
            array('tramite' => $request->get("Solicitud")));
    }

    /**
     * @Route("/jubilacion/tramites-atendidos-consejo", name="jubilacion-tramites-atendidos-consejo")
     */
    public function tramitesAtendidosConsejoAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $transicionRepository = $entityManager->getRepository('TramiteBundle:Transicion');
        $tramites = $transicionRepository->getListadoAprobadosNegados();

        return $this->render('JubilacionBundle::tramAtendidosConsejo.html.twig',
            array('tramites' => $tramites));
    }

    /**
     * @Route("/jubilacion/cargar-informe-consejo", name="jubilacion-cargar-informe-consejo")
     */
    public function cargarInformeConsejoAction()
    {
        return $this->render('JubilacionBundle::cargarInformeConsejo.html.twig');
    }

    /**
     * @Route("/jubilacion/cargar-informe-direccion", name="jubilacion-cargar-informe-direccion")
     */
    public function cargarInformeDireccionAction()
    {
        return $this->render('JubilacionBundle::cargarInformeDireccion.html.twig');
    }
}
