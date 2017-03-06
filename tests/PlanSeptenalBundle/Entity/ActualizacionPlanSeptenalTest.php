<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\ActualizacionPlanSeptenal;
use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class ActualizacionPlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TramitePlanSeptenal
     */
    protected $beca;

    /**
     * @var ActualizacionPlanSeptenal
     */
    protected $actualizacion_beca;

    /**
     * @var TramitePlanSeptenal
     */
    protected $sabatico;

    /**
     * @var ActualizacionPlanSeptenal
     */
    protected $actualizacion_sabatico;

    protected function setUp()
    {
        $this->beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $this->beca->setPeriodo(new MonthlyDateRange('01/2015', '06/2015'));

        $this->actualizacion_beca = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($this->beca)
            ->setNuevoPeriodo(new MonthlyDateRange('03/2015', '09/2015'))
            ->setMotivo('motivo');

        $this->sabatico = new TramitePlanSeptenal(['tipo' => 'sabatico']);
        $this->sabatico->setPeriodo(new MonthlyDateRange('07/2015', '12/2015'));

        $this->actualizacion_sabatico = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($this->sabatico)
            ->setNuevoPeriodo(new MonthlyDateRange('10/2016', '03/2017'))
            ->setMotivo('motivo');

        $this->actualizacion_plan_septenal = new ActualizacionPlanSeptenal();

        $this->actualizacion_plan_septenal->addActualizacionTramite($this->actualizacion_beca)
            ->addActualizacionTramite($this->actualizacion_sabatico);
    }

    public function testActualizacionPlanSeptenalMustHavePendingStatusAfterCreation()
    {
        $this->assertEquals('Pendiente', $this->actualizacion_plan_septenal->getEstado());
    }

    public function testActualizacionPlanSeptenalCanAffectSeveralTramites()
    {
        $this->actualizacion_plan_septenal->aprobar();

        $this->assertEquals('Aprobada', $this->actualizacion_plan_septenal->getEstado());
        $this->assertEquals($this->actualizacion_beca->getNuevoPeriodo(), $this->beca->getPeriodo());
        $this->assertEquals($this->actualizacion_sabatico->getNuevoPeriodo(), $this->sabatico->getPeriodo());
    }
}
