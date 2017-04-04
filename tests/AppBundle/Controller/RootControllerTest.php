<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RootControllerTest extends WebTestCase
{
    /**
     * @group functionalTesting
     */
    public function testUnauthenticatedUserShouldBeRedirectedToLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertStringEndsWith( 'login', $client->getResponse()->headers->get('location') );
    }

    /**
     * @group functionalTesting
     */
    public function testAuthenticatedUserShouldGetASuccessfulResponse()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
