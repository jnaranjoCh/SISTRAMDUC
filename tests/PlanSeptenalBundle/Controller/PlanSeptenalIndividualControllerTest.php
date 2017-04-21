<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Repository\PlanSeptenalIndividualRepository;
use AppBundle\Entity\Usuario;

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
        $this->plan_septenal_colectivo_repo = $this->em->getRepository(PlanSeptenalColectivo::class);
        $this->usuario_repo = $this->em->getRepository(Usuario::class);

        $this->tramite_repo = $this->em->getRepository(TramitePlanSeptenal::class);

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));

        $this->plan_septenal_individual_array = [
            'inicio'   => 2010,
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
    public function testGetActionFailsWhenRequestedPlanSeptenalIndividualDoesntExist()
    {
        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['inicio' => 2010]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testGetActionCanRetrievePlanById()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();

        $this->client->request(
            'GET',
            '/plan-septenal-individual',
            ['id' => $planSeptenalIndividual->getId()]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"inicio":2010,"status":"Modificando","owner":{"id":1,"nombre":"Anthony Stark"},"tramites":[]}', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testCreateActionFailsWhenCorrespondingPlanSeptenalColectivoDoesntExist()
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
    public function testCreateActionOnSuccess()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));

        $this->em->persist($planSeptenalColectivo);
        $this->em->flush();

        $this->client->request(
            'POST',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());

        $plan = $this->plan_septenal_individual_repo->findOneBy([]);

        $this->assertEquals(2010, $plan->getInicio());
        $this->assertEquals(2016, $plan->getFin());
        $this->assertEquals(2, $plan->getTramitesCount());
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
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testUpdateActionSuccess()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);
        $planSeptenalIndividual->addTramites($this->plan_septenal_individual_array['tramites']);

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();
        $this->em->clear();

        $this->plan_septenal_individual_array['tramites'] = [[
            'tipo' => 'licencia',
            'periodo' => [
                'start' => '02/2010',
                'end' => '09/2010'
            ]
        ]];

        $this->client->request(
            'PUT',
            '/plan-septenal-individual',
            $this->plan_septenal_individual_array
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());

        $plan = $this->plan_septenal_individual_repo->findOneBy([]);

        $this->plan_septenal_individual_array['status'] = 'Modificando';
        $this->plan_septenal_individual_array['owner'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombreCorto()
        ];
        $this->plan_septenal_individual_array['tramites'][0]['id'] = $plan->getTramites()[0]->getId();

        $this->assertEquals($this->plan_septenal_individual_array, $plan->toArray());
    }

    /**
     * @group functionalTesting
     */
    public function testAskForApprovalOnSuccess()
    {
        $criteria = ['inicio' => 2010];

        $usuario = $this->usuario_repo->findOneBy(['cedula' => '1234']);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();
        $this->em->clear();

        $this->client->request(
            'PUT',
            '/plan-septenal-individual/ask-for-approval',
            $criteria
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());

        $plan = $this->plan_septenal_individual_repo->findOneBy($criteria);
        $this->assertEquals('Esperando aprobación', $plan->getStatus());
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
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testGetAllAction()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);
        $planSeptenalIndividual->askForApproval();

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();

        $this->client->request(
            'GET',
            '/plan-septenal-individual/get-all',
            ['inicio' => 2010]
        );

        $list = '{"data":[[' . $planSeptenalIndividual->getId() . ',"'. $usuario->getNombreCompleto() .'",0,"Esperando aprobaci\u00f3n"]]}';
        $response = $this->client->getResponse();
        $this->assertEquals($list, $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testApproveActionPlanNotFound()
    {
        $this->client->request(
            'POST',
            '/plan-septenal-individual/approve',
            ['id' => 1]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('"Plan septenal individual no existe"', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testApproveActionInvalidPlan()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();

        $this->client->request(
            'POST',
            '/plan-septenal-individual/approve',
            ['id' => $planSeptenalIndividual->getId()]
        );
        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('"Plan debe estar en espera por aprobaci\u00f3n"', $response->getContent());
    }

    /**
     * @group functionalTesting
     */
    public function testApproveActionValidPlan()
    {
        $usuario = $this->usuario_repo->findOneBy([]);

        $planSeptenalColectivo = new PlanSeptenalColectivo(2010, $usuario, (new DateTime)->modify('+1 month'));
        $planSeptenalIndividual = new PlanSeptenalIndividual(2010, $usuario, $planSeptenalColectivo);
        $planSeptenalIndividual->askForApproval();

        $this->em->persist($planSeptenalColectivo);
        $this->em->persist($planSeptenalIndividual);
        $this->em->flush();
        $this->em->clear();

        $this->client->request(
            'POST',
            '/plan-septenal-individual/approve',
            ['id' => $planSeptenalIndividual->getId()]
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $planSeptenalIndividual = $this->plan_septenal_individual_repo->findOneBy([]);
        $this->assertEquals('Aprobado', $planSeptenalIndividual->getStatus());
    }

    protected function tearDown()
    {
        $tramites = $this->tramite_repo->findAll();
        foreach ($tramites as $tramite) {
            $this->em->remove($tramite);
        }

        $planesInd = $this->plan_septenal_individual_repo->findAll();
        foreach ($planesInd as $plan) {
            $this->em->remove($plan);
        }

        $planesCol = $this->plan_septenal_colectivo_repo->findAll();
        foreach ($planesCol as $plan) {
            $this->em->remove($plan);
        }

        $this->em->flush();
    }
}
