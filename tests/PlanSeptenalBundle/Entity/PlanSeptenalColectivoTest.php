<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class PlanSeptenalColectivoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException     Exception
     * @expectedExceptioncode 10
     */
    public function testPlanSeptenalColectivoMustBeSeptennial()
    {
        $planColectivo = new PlanSeptenalIndividual(2010, 2017);
    }

    public function testPlanSeptenalColectivoMustContainPlanesSeptenalesIndividualesAfterAdditions()
    {
        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('01/2014', '12/2014'));

        $planSeptenalIndividualUno = new PlanSeptenalIndividual(2010, 2016);
        $planSeptenalIndividualUno->addTramite($beca);
        $planSeptenalIndividualUno->addTramite($sabatico);

        $postgrado = (new TramitePlanSeptenal)
            ->setTipo('postgrado')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $licencia = (new TramitePlanSeptenal)
            ->setTipo('licencia')
            ->setPeriodo(new MonthlyDateRange('01/2014', '12/2014'));

        $planSeptenalIndividualDos = new PlanSeptenalIndividual(2010, 2016);
        $planSeptenalIndividualDos->addTramite($postgrado);
        $planSeptenalIndividualDos->addTramite($licencia);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016);
        $planSeptenalColectivo->addPlanSeptenalIndividual($planSeptenalIndividualUno);
        $planSeptenalColectivo->addPlanSeptenalIndividual($planSeptenalIndividualDos);

        $planes = $planSeptenalColectivo->getPlanesSeptenalesIndividuales();
        $this->assertContains($planSeptenalIndividualUno, $planes);
        $this->assertContains($planSeptenalIndividualDos, $planes);
    }

    /**
     * @expectedException     Exception
     * @expectedExceptioncode 10
     */
    public function testPlanesIndividualesDateRangeMustCoincideWithPlanColectivo()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2011, 2017);

        $planSeptenalColectivo->addPlanSeptenalIndividual($planSeptenalIndividual);
    }
}