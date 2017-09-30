<?php

namespace ClausulasContractualesABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReporteController extends Controller
{   

    public function indicadorDeReprocesoIndexAction()
    {
        return $this->render('ClausulasContractualesABundle:Reporte:indicador_de_reproceso.html.twig');
    }

    public function rendicionPorFrecuenciaIndexAction()
    {
        return $this->render('ClausulasContractualesABundle:Reporte:rendicion_por_frecuencia.html.twig');
    }
    
    public function rendicionPorMontoIndexAction()
    {
        return $this->render('ClausulasContractualesABundle:Reporte:rendicion_por_monto.html.twig');
    }
}
