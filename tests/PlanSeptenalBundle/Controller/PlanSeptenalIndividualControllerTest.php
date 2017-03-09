<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;

class PlanSeptenalIndividualController extends WebTestCase
{
    protected $em;

    /*
    public function setUp() {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
    */
    public function testCreateAction()
    {
        /*
        $client = static::createClient();

        $plan_septenal_individual_json = [
            'inicio'   => "2010",
            'fin'      => "2016",
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

        $client->request(
            'POST',
            '/plan-septenal/individual',
            $plan_septenal_individual_json
        );

        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertCount(2, $this->em->getRepository(TramitePlanSeptenal::class)->findAll());
        */
    }
}
