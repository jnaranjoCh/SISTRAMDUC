<?php

namespace JubilacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Hackzilla\BarcodeBundle\Utility\Barcode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TramiteBundle\Entity\Transicion;
use JubilacionBundle\Entity\TramiteJubilacion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Proxies\__CG__\TramiteBundle\Entity\Estado;

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
     * @Route("/jubilacion/consejo-facultad", name="jubilacion-consejo-facultad")
     */
    public function consejoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tramites = $em->getRepository(TramiteJubilacion::class);
        $tramites = $tramites->findAll();
        return $this->render('JubilacionBundle::consejoFacultad.html.twig',
            array('tramites' => $tramites));
        
        //return $this->render('JubilacionBundle::consejoFacultad.html.twig');
    }

    /**
     * @Route("/jubilacion/direccion-asuntos-Prof", name="jubilacion-direccion-asuntos-Prof")
     */
    public function direccionAction()
    {
        return $this->render('JubilacionBundle::dirAsuntosProfesorales.html.twig');
    }


    /**
     * @Route("/jubilacion/constacia-jubilacion", name="jubilacion-constancia-jubilacion")
     */
    public function constanciaJubilacionAction()
    {
        return $this->render('JubilacionBundle::constanciaJubilacion.html.twig');
    }

    /**
     * @Route("/jubilacion/informe-jubilacion", name="jubilacion-informe-jubilacion")
     */
    public function informeJubilacionAction()
    {
        return $this->render('JubilacionBundle::informeJubilacion.html.twig');
    }

    /**
     * @Route("/jubilacion/estado-solicitud", name="jubilacion-estado-solicitud")
     */
    public function estadoSolicitudAction()
    {
        return $this->render('JubilacionBundle::progresoSolicitudProf.html.twig');
    }

    /**
     * @Route("/jubilacion/codigo-barra", name="jubilacion-codigo-barra")
     * Muestra el codigo como una imagen png
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
     * @Route("/jubilacion/informe-jubilacion-pdf", name="jubilacion-informe-jubilacion-pdf")
     */
    public function informeJubilacionPDFAction()
    {
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('JubilacionBundle::informeJubilacion-print.html.twig', array(
            //..Send some data to your view if you need to //
        ));

        $filename = 'InformeJubilacion';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
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
}
