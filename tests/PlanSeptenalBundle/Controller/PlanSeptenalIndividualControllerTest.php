<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;

class PlanSeptenalIndividualController extends WebTestCase
{
    protected $plan_septenal_individual_json;
    protected $em;
    protected $plan_septenal_individual_repo;
    protected $tramite_repo;
    protected $client;

    public function setUp() {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $this->plan_septenal_individual_repo = $this->em->getRepository(PlanSeptenalIndividual::class);
        $this->tramite_repo = $this->em->getRepository(TramitePlanSeptenal::class);

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));

        $this->plan_septenal_individual_array = [
            'inicio'   => 2010,
            'fin'      => 2016,
            'tramites' => [
                [
                    'tipo' => 'beca',
                    'periodo' => [
                        'start' => '01/2010',
                        'end' => '03/2010'
                    ]
                ],
                [
                    'tipo' => 'licencia',
                    'periodo' => [
                        'start' => '05/2013',
                        'end' => '08/2013'
                    ]
                ]
            ]
        ];
    }

    /**
     * @group functionalTesting
     */
    public function testCreateOrUpdateActionCreateNewPlan()
    {
        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertCount(2, $this->tramite_repo->findAll());
        $this->assertCount(1, $this->plan_septenal_individual_repo->findAll());
    }

    /**
     * @group functionalTesting
     */
    public function testCreateOrUpdateActionUpdateOldPlan()
    {
        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->plan_septenal_individual_array['tramites'][] = [
            'tipo' => 'licencia',
            'periodo' => [
                'start' => '06/2011',
                'end' => '12/2011'
            ]
        ];

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertCount(3, $this->tramite_repo->findAll());
        $this->assertCount(1, $this->plan_septenal_individual_repo->findAll());
    }

    /**
     * @group functionalTesting
     */
    public function testGetAction()
    {
        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(
            $this->plan_septenal_individual_array['inicio'],
            $response['inicio']
        );

        $this->assertEquals(
            $this->plan_septenal_individual_array['fin'],
            $response['fin']
        );

        $this->assertContains(
            $this->plan_septenal_individual_array['tramites'][0],
            $response['tramites']
        );

        $this->assertContains(
            $this->plan_septenal_individual_array['tramites'][1],
            $response['tramites']
        );
    }

    protected function tearDown()
    {
        $tramites = $this->tramite_repo->findAll();
        foreach ($tramites as $tramite) {
            $this->em->remove($tramite);
        }

        $planes = $this->plan_septenal_individual_repo->findAll();
        foreach ($planes as $plan) {
            $this->em->remove($plan);
        }

        $this->em->flush();
    }
}
