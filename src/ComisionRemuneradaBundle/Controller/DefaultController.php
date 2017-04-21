<?php

namespace ComisionRemuneradaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        return $this->render('ComisionRemuneradaBundle:AAPP:solicitudes_serv_remun.html.twig');
    }
    /**
     * @Route("/estado-solicitud", name="estado-solicitud")
     */
    public function estado_sol_profAction()
    {
        return $this->render('ComisionRemuneradaBundle:Default:estado_sol_prof.html.twig');
    }

    /**
     * @Route("/solicitudes_serv_remun/envio-solicitud")
     */
    /*public function descargaArchivoAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $file = $request->files->get('oficio');
            var_dump($request->files->all());
            $status = array('status' => "success","fileUploaded" => false);
            if(!is_null($file))
            {
                $filename = uniqid().".".$file->getClientOriginalExtension();
                $path = "C:\Users\Anyelys\Downloads";
                $file->move($path,$filename);
                $status = array('status' => "success","fileUploaded" => true);
            }
            return new JsonResponse($status);
        }
        else
            throw $this->createNotFoundException('Error al solicitar datos');
    }*/
}
