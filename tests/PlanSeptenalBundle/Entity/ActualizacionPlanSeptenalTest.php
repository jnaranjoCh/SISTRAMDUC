<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\ActualizacionPlanSeptenal;
use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;

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
        $this->beca->setMesInicial('01/2015')->setMesFinal('06/2015');

        $this->actualizacion_beca = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($this->beca)
            ->setNuevoRango('03/2015', '09/2015')
            ->setMotivo('motivo');

        $this->sabatico = new TramitePlanSeptenal(['tipo' => 'sabatico']);
        $this->sabatico->setMesInicial('07/2015')->setMesFinal('12/2015');

        $this->actualizacion_sabatico = (new ActualizacionTramitePlanSeptenal)
            ->setTramite($this->sabatico)
            ->setNuevoRango('10/2016', '03/2017')
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

        $this->assertEquals($this->actualizacion_beca->getMesInicial(), $this->beca->getMesInicial());
        $this->assertEquals($this->actualizacion_beca->getMesFinal(), $this->beca->getMesFinal());

        $this->assertEquals($this->actualizacion_sabatico->getMesInicial(), $this->sabatico->getMesInicial());
        $this->assertEquals($this->actualizacion_sabatico->getMesFinal(), $this->sabatico->getMesFinal());
    }
}
