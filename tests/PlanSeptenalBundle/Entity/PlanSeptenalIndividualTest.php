<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;
use AppBundle\Entity\Usuario;

class PlanSeptenalIndividualTest extends \PHPUnit_Framework_TestCase
{
    protected static $arrayRepresentation;
    protected static $planSeptenalIndividual;
    protected static $beca;
    protected static $licencia;

    public function testPlanSeptenalIndividualMustContainTramitesAfterAddition()
    {
        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('01/2014', '12/2014'));

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
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

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());

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

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());

        $planSeptenalIndividual->addTramite($beca);
        $planSeptenalIndividual->addTramite($sabatico);
    }

    public function testAskForApprovalShouldModifyStatus()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->askForApproval();

        $this->assertEquals('Esperando aprobación', $planSeptenalIndividual->getStatus());
    }

    public function testGetTramitesCount()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());

        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2010', '03/2010'));
        $planSeptenalIndividual->addTramite($beca);
        $this->assertEquals(1, $planSeptenalIndividual->getTramitesCount());

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('05/2013', '08/2013'));
        $planSeptenalIndividual->addTramite($sabatico);
        $this->assertEquals(2, $planSeptenalIndividual->getTramitesCount());
    }

    public function testGetOwnerName()
    {
        $usuario = $this->getMockBuilder(Usuario::class)
            ->disableOriginalConstructor()
            ->setMethods(['getNombreCompleto'])
            ->getMock();

        $usuario->expects($this->once())
            ->method('getNombreCompleto')
            ->will($this->returnValue("Tony"));

        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario);

        $this->assertEquals("Tony", $planSeptenalIndividual->getOwnerName());
    }

    /**
      * @expectedException     Exception
      * @expectedExceptionCode 100
      */
    public function testPlanMustBeWaitingForApprovalToBeApprovable()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->approve();
    }

    public function testApprove()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->askForApproval()->approve();

        $this->assertEquals($planSeptenalIndividual->getStatus(), 'Aprobado');
    }
}
