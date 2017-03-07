<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;

class PlanSeptenalIndividualController extends WebTestCase
{
    protected $em;

    public function setUp() {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @group functionalTesting
     */
    public function testCreateAction()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => '1234',
            'PHP_AUTH_PW'   => '1234',
        ));

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

        $tramites = $this->em->getRepository(TramitePlanSeptenal::class)->findAll();
        $this->assertCount(2, $tramites);

        $planes = $this->em->getRepository(PlanSeptenalIndividual::class)->findAll();
        $this->assertCount(1, $planes);

        $this->em->remove($tramites[0]);
        $this->em->remove($tramites[1]);
        $this->em->remove($planes[0]);
        $this->em->flush();
    }
}
