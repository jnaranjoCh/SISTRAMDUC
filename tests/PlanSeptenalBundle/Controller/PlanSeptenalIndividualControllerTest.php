<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use AppBundle\Entity\Usuario;

class PlanSeptenalIndividualController extends WebTestCase
{
    protected $plan_septenal_individual_json;
    protected $em;
    protected $plan_septenal_individual_repo;
    protected $usuario;
    protected $tramite_repo;
    protected $client;

    public function setUp() {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->em = $kernel->getContainer()->get('doctrine')->getManager();

        $this->plan_septenal_individual_repo = $this->em->getRepository(PlanSeptenalIndividual::class);
        $this->plan_septenal_colectivo_repo = $this->em->getRepository(PlanSeptenalColectivo::class);
        $this->usuario_repo = $this->em->getRepository(Usuario::class);

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
    public function testGetActionWouldFailIfRequestedPlanSeptenalIndividualDoesntExist()
    {
        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe."]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testCreateActionWouldFailIfCorrespondingPlanSeptenalColectivoDoesntExist()
    {
        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('["Error. El plan colectivo correspondiente no existe"]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testUpdateActionWouldFailIfCorrespondingPlanSeptenalIndividualDoesntExist()
    {
        $this->client->request(
            'PUT',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe."]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testCreateCreateGetUpdate()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $usuario, (new DateTime)->modify('+1 month'));

        $this->em->persist($planSeptenalColectivo);
        $this->em->flush();

        $this->assertCount(1, $this->plan_septenal_colectivo_repo->findAll());

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertCount(1, $this->plan_septenal_individual_repo->findAll());

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual ya existe"]', $response->getContent());

        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $plan = json_decode($response->getContent(), true);

        $this->assertEquals($plan['inicio'], $this->plan_septenal_individual_array['inicio']);
        $this->assertEquals($plan['fin'], $this->plan_septenal_individual_array['fin']);
        $this->assertContains($plan['tramites'][0], $this->plan_septenal_individual_array['tramites']);
        $this->assertContains($plan['tramites'][1], $this->plan_septenal_individual_array['tramites']);

        $this->client->request(
            'PUT',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $this->plan_septenal_individual_repo->findAll());
    }

    /**
     * @group functionalTesting
     */
    public function testAskForApproval()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        // it's not the point of this test to confirm the creation works as expected
        // but we require an existing plan septenal individual
        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, 2016, $usuario, (new DateTime)->modify('+1 month'));

        $this->em->persist($planSeptenalColectivo);
        $this->em->flush();

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $this->client->request(
            'PUT',
            '/plan-septenal-individual/ask-for-approval',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010, 'fin' => 2016]
        );

        $response = $this->client->getResponse();
        $plan = json_decode($response->getContent(), true);
        $this->assertEquals('Esperando aprobación', $plan['status']);
    }

    /**
     * @group functionalTesting
     */
    public function testAskForApprovalActionWouldFailIfCorrespondingPlanSeptenalIndividualDoesntExist()
    {
        $this->client->request(
            'PUT',
            '/plan-septenal-individual/ask-for-approval',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe."]', $response->getContent());
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

        $planes = $this->plan_septenal_colectivo_repo->findAll();
        foreach ($planes as $plan) {
            $this->em->remove($plan);
        }

        $this->em->flush();
    }
}
