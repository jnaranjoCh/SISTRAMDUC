<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class PlanSeptenalIndividualTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @expectedException     Exception
      * @expectedExceptionCode 10
      */
    public function testPlanSeptenalIndividualMustBeSeptennial()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2015);
    }

    public function testPlanSeptenalIndividualMustContainTramitesAfterAddition()
    {
        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('01/2014', '12/2014'));

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);
        $planSeptenalIndividual->addTramite($beca);
        $planSeptenalIndividual->addTramite($sabatico);

        $tramites = $planSeptenalIndividual->getTramites();

        $this->assertContains($beca, $tramites);
        $this->assertContains($sabatico, $tramites);
    }

    /**
      * @expectedException     Exception
      * @expectedExceptionCode 20
      */
    public function testTramiteMustBeWithinPlanSeptenalYears()
    {
        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2009', '06/2009'));

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);

        $planSeptenalIndividual->addTramite($beca);
    }

    /**
      * @expectedException     Exception
      * @expectedExceptionCode 30
      */
    public function testTramiteDateRangesMustBeDisjoint()
    {
        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('04/2016', '08/2016'));

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);

        $planSeptenalIndividual->addTramite($beca);
        $planSeptenalIndividual->addTramite($sabatico);
    }
}
