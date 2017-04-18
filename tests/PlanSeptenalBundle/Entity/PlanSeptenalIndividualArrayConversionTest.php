<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;
use AppBundle\Entity\Usuario;

class PlanSeptenalIndividualArrayConversionTest extends \PHPUnit_Framework_TestCase
{
    private $arrayRepresentation;
    private $usuario;
    private $planSeptenalIndividual;
    private $beca;
    private $licencia;

    public function setUp()
    {
        $this->arrayRepresentation = [
            'inicio'   => 2010,
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

        $this->usuario = $this->getMockBuilder(Usuario::class)
            ->disableOriginalConstructor()
            ->setMethods(['getNombreCorto', 'getId'])
            ->getMock();

        $this->usuario->expects($this->any())
            ->method('getNombreCorto')
            ->will($this->returnValue("Tony Stark"));

        $this->usuario->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $this->planSeptenalIndividual = new PlanSeptenalIndividual(2010, $this->usuario);

        $this->beca = (new TramitePlanSeptenal)
            ->setTipo('beca')
            ->setPeriodo(new MonthlyDateRange('01/2010', '03/2010'));

        $this->licencia = (new TramitePlanSeptenal)
            ->setTipo('licencia')
            ->setPeriodo(new MonthlyDateRange('05/2013', '08/2013'));

        $this->planSeptenalIndividual->addTramite($this->beca);
        $this->planSeptenalIndividual->addTramite($this->licencia);
    }

    public function testCreateFromArrayMethod()
    {
        $this->arrayRepresentation['owner'] = $this->usuario;

        $this->assertEquals(
            $this->planSeptenalIndividual,
            PlanSeptenalIndividual::createFromArray($this->arrayRepresentation)
        );
    }

    public function testAddTramitesInArrayForm()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $this->usuario);
        $planSeptenalIndividual->addTramites($this->arrayRepresentation['tramites']);

        $this->assertEquals(
            $this->planSeptenalIndividual->getTramites(),
            $planSeptenalIndividual->getTramites()
        );
    }

    public function testAddTramitesInClassForm()
    {
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, new Usuario());
        $planSeptenalIndividual->addTramites([$this->beca, $this->licencia]);

        $this->assertEquals(
            $this->planSeptenalIndividual->getTramites(),
            $planSeptenalIndividual->getTramites()
        );
    }

    public function testToArray()
    {
        $this->arrayRepresentation['owner'] = [
            'id' => $this->usuario->getId(),
            'nombre' => $this->usuario->getNombreCorto()
        ];

        $this->assertEquals(
            $this->arrayRepresentation,
            $this->planSeptenalIndividual->toArray()
        );
    }
}