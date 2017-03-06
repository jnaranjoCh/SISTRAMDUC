<?php

namespace Tests\PlanSeptenal\ValueObject;

use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class MonthlyDateRangeTest extends \PHPUnit_Framework_TestCase
{
    /**
      * @expectedException     Exception
      * @expectedExceptionCode 100
      */
    public function testMesInicialCannotBeAfterMesFinal()
    {
        $invalid_range = new MonthlyDateRange('02/2017', '01/2017');
    }

    public function testExtraDataIsExcludedDuringCreationFromDateTimes()
    {
        $range_from_strings = new MonthlyDateRange('01/2017', '03/2017');

        $range_from_datetimes  = new MonthlyDateRange(
            new \DateTime('2017-01-20'), new \DateTime('2017-03-22')
        );

        $this->assertEquals($range_from_strings, $range_from_datetimes);
    }

    public function testOverlapping()
    {
        $range_one = new MonthlyDateRange('01/2017', '08/2017');
        $range_two = new MonthlyDateRange('04/2017', '12/2017');

        $this->assertTrue($range_one->overlaps($range_two));

        $range_three = new MonthlyDateRange('09/2017', '12/2017');

        $this->assertFalse($range_one->overlaps($range_three));
    }

    public function testDisjointness()
    {
        $range_one = new MonthlyDateRange('01/2017', '08/2017');
        $range_two = new MonthlyDateRange('04/2017', '12/2017');

        $this->assertFalse($range_one->isDisjoint($range_two));

        $range_three = new MonthlyDateRange('09/2017', '12/2017');

        $this->assertTrue($range_one->isDisjoint($range_three));
    }

    public function testGettersReturnNewDatetimeObjects()
    {
        $range = new MonthlyDateRange('01/2010', '06/2010');
        $copy = new MonthlyDateRange('01/2010', '06/2010');

        $start = $range->getStart()->modify('+1 day');
        $this->assertEquals($copy->getStart(), $range->getStart());

        $end = $range->getEnd()->modify('+1 day');
        $this->assertEquals($copy->getEnd(), $range->getEnd());
    }
}
