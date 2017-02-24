<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class ActualizacionTramitePlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestedActualizacionPlanSeptenalMustBeReachableFromTramite()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setPeriodo(new MonthlyDateRange('05/2016', '09/2016'));

        $actualizacion = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($beca)
            ->setNuevoPeriodo(new MonthlyDateRange('07/2016', '02/2017'))
            ->setMotivo('motivo');

        $this->assertContains($actualizacion, $beca->getActualizacionesSolicitadas());
    }
}
