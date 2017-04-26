<?php

namespace ComisionRemuneradaBundle\Controller;

use Proxies\__CG__\AppBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Hackzilla\BarcodeBundle\Utility\Barcode;
use TramiteBundle\Entity\Tramite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/comision-servicio-remunerada/info", name="comision-servicio-remunerada-info")
     */
    public function comision_remunerada_infoAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:comision_remunerada_info.html.twig');
    }
    /**
     * @Route("/solicitud_serv_remun", name="solicitud_serv_remun")
     */
    public function solicitud_serv_remunAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:solicitud_serv_remun.html.twig');
    }
    /**
     * @Route("/solicitudes_serv_remun", name="solicitudes_serv_remun")
     */
    public function solicitudes_serv_remunAction()
    {
        $em = $this->getDoctrine()->getManager();
        /*$tramites = $em->getRepository(Tramite::class);
        $tramites_comision = $tramites->findBy(["tipo_tramite_id" => 6]);*/
        $query = $em->createQuery("SELECT u, t FROM TramiteBundle:Tramite t JOIN t.usuario_id u WHERE t.usuario_id = u.id AND t.tipo_tramite_id = 6");
        $tramites_comision = $query->getResult();
        return $this->render('ComisionRemuneradaBundle:AAPP:solicitudes_serv_remun.html.twig',
            array('tramites_comision' => $tramites_comision));
    }
    /**
     * @Route("/estado-solicitud", name="estado-solicitud")
     */
    public function estado_sol_profAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:estado_sol_prof.html.twig');
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

        $html = $this->renderView('ComisionRemuneradaBundle:Rectora:informeJubilacion-print.html.twig', array(
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
}
