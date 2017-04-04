<?php

namespace PlanSeptenalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BundleAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @group functionalTesting
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));

        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/plan-septenal/colectivo'),
            array('/plan-septenal/individual'),
        );
    }
}
