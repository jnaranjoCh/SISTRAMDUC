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

    /**
     * @beforeClass
     */
    public static function setUpSomeSharedFixtures()
    {
        static::$arrayRepresentation = [
            'inicio'   => 2010,
            'fin'      => 2016,
            'status'   => 'Modificando',
            'tramites' => [
                [
                    'tipo' => 'beca',
                    'periodo' => [
                        'start' => '01/2010',
                        'end' => '03/2010'
                    ]
                ],
                [
                    'tipo' => 'licencia',
                    'periodo' => [
                        'start' => '05/2013',
                        'end' => '08/2013'
                    ]
                ]
            ]
        ];

        static::$planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());

        static::$beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2010', '03/2010'));

        static::$licencia = (new TramitePlanSeptenal)
            ->setTipo('licencia')
            ->setPeriodo(new MonthlyDateRange('05/2013', '08/2013'));

        static::$planSeptenalIndividual->addTramite(static::$beca);
        static::$planSeptenalIndividual->addTramite(static::$licencia);
    }

    public function testCreateFromArrayMethod()
    {
        $planIndividual = static::$arrayRepresentation;
        $planIndividual['owner'] = new Usuario();

        $this->assertEquals(
            static::$planSeptenalIndividual,
            PlanSeptenalIndividual::createFromArray($planIndividual)
        );
    }

    public function testAddTramitesInArrayForm()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->addTramites(static::$arrayRepresentation['tramites']);

        $this->assertEquals(
            static::$planSeptenalIndividual->getTramites(),
            $planSeptenalIndividual->getTramites()
        );
    }

    public function testAddTramitesInClassForm()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->addTramites([static::$beca, static::$licencia]);

        $this->assertEquals(
            static::$planSeptenalIndividual->getTramites(),
            $planSeptenalIndividual->getTramites()
        );
    }

    public function testToArray()
    {
        $this->assertEquals(
            static::$arrayRepresentation,
            static::$planSeptenalIndividual->toArray()
        );
    }
}
