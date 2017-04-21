<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\Departamento;

class PlanSeptenalColectivoArrayConversionTest extends \PHPUnit_Framework_TestCase
{
    private $creator;
    private $creator_department;
    private $creation_deadline;
    private $planSeptenalIndividual;

    public function setUp()
    {
        $this->creation_deadline = new \DateTime('tomorrow');

        $this->creator = $this->getMockBuilder(Usuario::class)
            ->disableOriginalConstructor()
            ->setMethods(['getNombreCorto', 'getId', 'getDepartamento'])
            ->getMock();

        $this->creator->expects($this->any())
            ->method('getNombreCorto')
            ->will($this->returnValue("Tony Stark"));

        $this->creator->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $this->creator->expects($this->any())
            ->method('getDepartamento')
            ->will( $this->returnValue(new Departamento()) );

        $beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2016', '06/2016'));

        $this->planSeptenalIndividual = new PlanSeptenalIndividual(2010, $this->creator);
        $this->planSeptenalIndividual->addTramite($beca);
    }

    public function testToArray()
    {
        $planColectivo = new PlanSeptenalColectivo(2010, $this->creator, $this->creation_deadline);
        $planColectivo->addPlanSeptenalIndividual($this->planSeptenalIndividual);

        $planColectivoArray = [
            'inicio' => $planColectivo->getInicio(),
            'status' => $planColectivo->getStatus(),
            'planes' => [
                $this->planSeptenalIndividual->toArray()
            ]
        ];

        $this->assertEquals($planColectivoArray, $planColectivo->toArray());
    }
}
