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
}
