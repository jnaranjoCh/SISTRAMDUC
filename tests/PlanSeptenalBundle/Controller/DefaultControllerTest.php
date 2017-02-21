<?php

namespace PlanSeptenalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/plan-septenal/colectivo');

        $this->assertContains('Plan Septenal Colectivo', $client->getResponse()->getContent());
    }
}
