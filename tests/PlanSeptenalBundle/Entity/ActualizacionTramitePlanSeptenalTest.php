<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;

class ActualizacionTramitePlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestedActualizacionPlanSeptenalMustBeReachableFromTramite()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('05/2016')->setMesFinal('09/2016');

        $actualizacion = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($beca)
            ->setNuevoRango('07/2016', '02/2017')
            ->setMotivo('motivo');

        $this->assertContains($actualizacion, $beca->getActualizacionesSolicitadas());
    }
}
