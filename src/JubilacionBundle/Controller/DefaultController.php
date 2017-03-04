<?php

namespace JubilacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        return $this->render('JubilacionBundle::consejoFacultad.html.twig');
    }

    /**
     * @Route("/jubilacion/direccion-asuntos-Prof", name="jubilacion-direccion-asuntos-Prof")
     */
    public function direccionAction()
    {
        return $this->render('JubilacionBundle::dirAsuntosProfesorales.html.twig');
    }

    /**
     * @Route("/jubilacion/informe-consejo", name="jubilacion-informe-consejo")
     */
    public function informeConsejoAction()
    {
        return $this->render('JubilacionBundle::informeConsejo.html.twig');
    }
}
