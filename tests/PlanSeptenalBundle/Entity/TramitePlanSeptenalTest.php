<?php

namespace Tests\PlanSeptenal\Entity;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;

class TramitePlanSeptenalTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @expectedException     Exception
      * @expectedExceptionCode 100
      */
    public function testTramitePlanSeptenalDateRangeValidation()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('06/2017');
        $beca->setMesFinal('01/2017');
    }

    public function testDateTimeIsWithinTramiteDateRange()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('01/2017');
        $beca->setMesFinal('06/2017');

        $date = new \DateTime('2017-01-10');

        $this->assertGreaterThanOrEqual($beca->getMesInicial(), $date);
        $this->assertLessThanOrEqual($beca->getMesFinal(), $date);
    }

    public function testDateTimeIsOutsideTramiteDateRange()
    {
        $beca = new TramitePlanSeptenal(['tipo' => 'beca']);
        $beca->setMesInicial('01/2017');
        $beca->setMesFinal('06/2017');

        $date = new \DateTime('2016-12-31');
        $this->assertLessThanOrEqual($beca->getMesInicial(), $date);

        $date = new \DateTime('2017-07-01');
        $this->assertGreaterThanOrEqual($beca->getMesFinal(), $date);
    }
}
