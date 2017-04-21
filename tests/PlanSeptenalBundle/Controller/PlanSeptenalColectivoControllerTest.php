<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;

class PlanSeptenalColectivoControllerTest extends WebTestCase
{
    protected $plan_septenal_colectivo;

    public function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->em = $kernel->getContainer()->get('doctrine')->getManager();

        $this->plan_septenal_colectivo_repo = $this->em->getRepository(PlanSeptenalColectivo::class);

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));
    }

    /**
     * @group functionalTesting
     */
    public function testStartCreationProcess()
    {
        $data = [
            'inicio' => 2010,
            'creation_deadline' => (new \DateTime('tomorrow'))->format("d/m/Y")
        ];

        $this->client->request(
            'POST',
            '/plan-septenal-colectivo/start-creation',
            $data
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertCount(1, $this->plan_septenal_colectivo_repo->findAll());
    }

    /**
     * @group functionalTesting
     */
    public function testGetOnNonExistentPlanShouldReturn404()
    {
        $data = ['inicio' => 2010];

        $this->client->request(
            'GET',
            '/plan-septenal-colectivo',
            $data
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    protected function tearDown()
    {
        $planes = $this->plan_septenal_colectivo_repo->findAll();
        foreach ($planes as $plan) {
            $this->em->remove($plan);
        }

        $this->em->flush();
    }
}
