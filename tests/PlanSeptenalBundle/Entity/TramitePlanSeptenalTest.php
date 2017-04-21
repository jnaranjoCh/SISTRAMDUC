<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class TramitePlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @expectedException     Exception
      * @expectedExceptionCode 100
      */
    public function testMesInicialCannotBeAfterMesFinal()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setPeriodo(new MonthlyDateRange('02/2017', '01/2017'));
    }

    public function testTramiteDateRangeMustIncludeBothMesInicialAndMesFinalCompletely()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setPeriodo(new MonthlyDateRange('01/2017', '06/2017'));

        $left_edge = \DateTime::createFromFormat('d-m-Y', '01-01-2017')->setTime(0, 0, 0);
        $this->assertGreaterThanOrEqual($beca->getPeriodo()->getStart(), $left_edge);

        $right_edge = \DateTime::createFromFormat('d-m-Y', '30-06-2017')->setTime(23, 59, 59);
        $this->assertLessThanOrEqual($beca->getPeriodo()->getEnd(), $right_edge);
    }

    public function testCreateFromArray()
    {
        $beca_from_constructor = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca_from_constructor->setPeriodo(new MonthlyDateRange('01/2017', '06/2017'));

        $beca_from_array = TramitePlanSeptenal::createFromArray([
            'tipo' => 'beca',
            'periodo' => [
                'start' => '01/2017',
                'end' => '06/2017'
            ]
        ]);

        $this->assertEquals($beca_from_constructor, $beca_from_array);
    }

    public function testToArray()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setPeriodo(new MonthlyDateRange('01/2017', '06/2017'));

        $beca_array = [
            'id' => null,
            'tipo' => 'beca',
            'periodo' => [
                'start' => '01/2017',
                'end' => '06/2017'
            ]
        ];

        $this->assertEquals($beca_array, $beca->toArray());
    }
}
