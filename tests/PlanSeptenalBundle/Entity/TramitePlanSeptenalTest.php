<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;

class TramitePlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @expectedException     Exception
      * @expectedExceptionCode 100
      */
    public function testMesInicialCannotBeAfterMesFinal()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('02/2017');
        $beca->setMesFinal('01/2017');
    }

    public function testTramiteDateRangeMustIncludeBothMesInicialAndMesFinalCompletely()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('01/2017');
        $beca->setMesFinal('06/2017');

        $left_edge = \DateTime::createFromFormat('d-m-Y', '01-01-2017');
        $left_edge->setTime(0, 0, 0);
        $this->assertGreaterThanOrEqual($beca->getMesInicial(), $left_edge);

        $right_edge = \DateTime::createFromFormat('d-m-Y', '30-06-2017');
        $right_edge->setTime(23, 59, 59);
        $this->assertLessThanOrEqual($beca->getMesFinal(), $right_edge);
    }
}
