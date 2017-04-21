<?php

use PlanSeptenalBundle\Utils\Helpers;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterKeys()
    {
        $target = [
            'id' => 1,
            'age' => 20,
            'first_name' => 'John',
            'last_name' => 'Wood'
        ];

        $allowed = ['id', 'age'];
        $filtered = Helpers::filterKeys($target, $allowed);
        $this->assertEquals(['id' => 1, 'age' => 20], $filtered);

        $allowed = ['gadget'];
        $filtered = Helpers::filterKeys($target, $allowed);
        $this->assertEquals([], $filtered);

        $allowed = ['id', 'age', 'first_name', 'last_name'];
        $filtered = Helpers::filterKeys($target, $allowed);
        $this->assertEquals($target, $filtered);
    }
}