<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\Departamento;

class PlanSeptenalColectivoTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->creator = new Usuario();
        $this->creator_department = new Departamento();
        $this->creator->setDepartamento($this->creator_department);

        $this->creation_deadline = (new \DateTime())->modify('+1 day');

        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $sabatico = (new TramitePlanSeptenal)
            ->setTipo('sabatico')
            ->setPeriodo(new MonthlyDateRange('01/2014', '12/2014'));

        $this->planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);
        $this->planSeptenalIndividual->addTramite($beca);
        $this->planSeptenalIndividual->addTramite($sabatico);
    }

    /**
     * @expectedException     Exception
     * @expectedExceptioncode 10
     */
    public function testPlanSeptenalColectivoMustBeSeptennial()
    {
        $planColectivo = new PlanSeptenalColectivo(2010, 2017, $this->creator, $this->creation_deadline);
    }

    public function testPlanSeptenalColectivoMustContainPlanSeptenalIndividualAfterAddition()
    {
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $this->creator, $this->creation_deadline);
        $planSeptenalColectivo->addPlanSeptenalIndividual($this->planSeptenalIndividual);

        $planes = $planSeptenalColectivo->getPlanesSeptenalesIndividuales();
        $this->assertContains($this->planSeptenalIndividual, $planes);
    }

    /**
     * @expectedException     Exception
     * @expectedExceptionCode 20
     */
    public function testPlanesIndividualesDateRangeMustCoincideWithPlanColectivo()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, 2016);
        $planSeptenalColectivo = new PlanSeptenalColectivo(2011, 2017, $this->creator, $this->creation_deadline);

        $planSeptenalColectivo->addPlanSeptenalIndividual($planSeptenalIndividual);
    }

    /**
     * @expectedException     Exception
     * @expectedExceptionCode 40
     */
    public function testCreatorShouldHaveADefinedDepartment()
    {
        $creator = new Usuario();
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $creator, $this->creation_deadline);
    }

    public function testNewlyCreatedPlanShouldHaveOnCreationStatus()
    {
        $this->creator->setDepartamento(new Departamento);
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $this->creator, $this->creation_deadline);

        $this->assertEquals('En creación', $planSeptenalColectivo->getStatus());
    }

    public function testNewlyCreatedPlanShouldBorrowDepartmentFromCreator()
    {
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $this->creator, $this->creation_deadline);

        $this->assertEquals($this->creator_department, $planSeptenalColectivo->getDepartamento());
    }

    /**
     * @expectedException     Exception
     * @expectedExceptionCode 50
     */
    public function testCreationDeadlineMustBeAfterCurrentDate()
    {
        $yesterday = (new \DateTime())->modify('-1 day');
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $this->creator, $yesterday);
    }
}