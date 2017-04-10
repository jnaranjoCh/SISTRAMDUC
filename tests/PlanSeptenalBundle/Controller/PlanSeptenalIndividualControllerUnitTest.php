<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Controller\PlanSeptenalIndividualController;
use PlanSeptenalBundle\Repository\PlanSeptenalIndividualRepository;
use PlanSeptenalBundle\Repository\PlanSeptenalColectivoRepository;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;

use AppBundle\Entity\Usuario;

class PlanIndividualTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAllAction()
    {
        $criteria = ['inicio' => 2011];

        $tony = (new Usuario())->setPrimerNombre('Anthony');
        $planIndividualOne = new PlanSeptenalIndividual(2011, $tony);

        $bob = (new Usuario())->setPrimerNombre('Bob');
        $planIndividualTwo = new PlanSeptenalIndividual(2011, $bob);

        $repositoryIndividual = $this->createMock(PlanSeptenalIndividualRepository::class);
        $repositoryIndividual->expects($this->once())
            ->method('findBy')
            ->with($criteria)
            ->will($this->returnValue( new ArrayCollection([$planIndividualOne, $planIndividualTwo]) ));

        $container = $this->getContainer(null, $repositoryIndividual);

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->getAllAction(new Request($criteria));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('{"data":[[null,"Anthony",0,"Modificando"],[null,"Bob",0,"Modificando"]]}', $response->getContent());
    }

    public function testGetActionOnNotFound()
    {
        $container = $this->getContainer(null, $this->getRepositoryIndividual(), $this->getTokenStorage());

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->getAction(new Request());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    public function testGetActionOnFound()
    {
        $planIndividual = $this->getMockBuilder(PlanSeptenalIndividual::class)
              ->disableOriginalConstructor()
              ->setMethods(['toArray'])
              ->getMock();

        $planIndividual
            ->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(['plan septenal individual']));

        $container = $this->getContainer(null, $this->getRepositoryIndividual($planIndividual), $this->getTokenStorage());

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->getAction(new Request());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["plan septenal individual"]', $response->getContent());
    }

    public function testCreateActionWhenPlanColectivoDoesNotExist()
    {
        $container = $this->getContainer($this->getDoctrine(), null, null, $this->getRepositoryColectivo());

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->createAction(new Request());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('["Error. El plan colectivo correspondiente no existe"]', $response->getContent());
    }

    public function testCreateActionWhenPlanColectivoExistsAndPlanIndividualDoesNot()
    {
        $doctrine = $this->getDoctrine();

        $entityManager = $doctrine->getManager();
        $entityManager->expects($this->once())
            ->method('persist');
        $entityManager->expects($this->once())
            ->method('flush');

        $planColectivo = $this->createMock(PlanSeptenalColectivo::class);

        $container = $this->getContainer(
            $doctrine,
            $this->getRepositoryIndividual(),
            $this->getTokenStorage(),
            $this->getRepositoryColectivo($planColectivo)
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $request = new Request([], ["inicio" => 2011, "fin" => 2017, "tramites" => []]);
        $response = $controller->createAction($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());
    }

    public function testCreateActionWhenPlanIndividualAlreadyExists()
    {
        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);
        $planColectivo = $this->createMock(PlanSeptenalColectivo::class);

        $container = $this->getContainer(
            $this->getDoctrine(),
            $this->getRepositoryIndividual($planIndividual),
            $this->getTokenStorage(),
            $this->getRepositoryColectivo($planColectivo)
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $request = new Request([], ["inicio" => 2011, "fin" => 2017, "tramites" => []]);
        $response = $controller->createAction($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual ya existe"]', $response->getContent());
    }

    public function testUpdateActionWhenPlanIndividualDoesNotExits()
    {
        $criteria = ["inicio" => 2011];

        $container = $this->getContainer(
            $this->getDoctrine(),
            $this->getRepositoryIndividual(null, array_merge($criteria, ["owner" => null])),
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $request = new Request([], $criteria);
        $response = $controller->updateAction($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    public function testUpdateActionWhenPlanIndividualExitsAndHaveNoTramites()
    {
        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);

        $tramites = ["tramite1", "tramite2"]; // no real tramites for the sake of simplicity
        $criteria = ["inicio" => 2011];

        $planIndividual
            ->expects($this->once())
            ->method('getTramites')
            ->will($this->returnValue(new ArrayCollection()));

        $planIndividual
            ->expects($this->once())
            ->method('addTramites')
            ->with($tramites);

        $container = $this->getContainer(
            $this->getDoctrine($planIndividual),
            $this->getRepositoryIndividual($planIndividual, array_merge($criteria, ['owner' => null])),
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $request = new Request([], array_merge($criteria, ['tramites' => $tramites]));
        $response = $controller->updateAction($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());
    }

    public function testUpdateActionWhenPlanIndividualExitsAndHaveSomeTramites()
    {
        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);

        $tramites = ["tramite3", "tramite4"]; // no real tramites for the sake of simplicity
        $criteria = ["inicio" => 2011];

        $planIndividual
            ->expects($this->once())
            ->method('getTramites')
            ->will($this->returnValue(new ArrayCollection(["tramite1", "tramite2"])));

        $planIndividual
            ->expects($this->once())
            ->method('addTramites')
            ->with($tramites);

        $doctrine = $this->getDoctrine($planIndividual);
        $doctrine->getManager()
            ->expects($this->exactly(2))
            ->method('remove');

        $container = $this->getContainer(
            $doctrine,
            $this->getRepositoryIndividual($planIndividual, array_merge($criteria, ['owner' => null])),
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $request = new Request([], array_merge($criteria, ['tramites' => $tramites]));
        $response = $controller->updateAction($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());
    }

    public function testAskForApprovalActionWhenPlanDoesNotExist()
    {
        $criteria = ["inicio" => 2011];

        $container = $this->getContainer(
            $this->getDoctrine(),
            $this->getRepositoryIndividual(),
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->askForApprovalAction(new Request([], $criteria));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('["El plan septenal individual no existe"]', $response->getContent());
    }

    public function testAskForApprovalActionWhenPlanExists()
    {
        $criteria = ["inicio" => 2011];

        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);

        $planIndividual->expects($this->once())
            ->method('askForApproval');

        $container = $this->getContainer(
            $this->getDoctrine($planIndividual),
            $this->getRepositoryIndividual($planIndividual, array_merge($criteria, ['owner' => null])),
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->askForApprovalAction(new Request([], $criteria));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());
    }

    public function testApproveActionOnNonexistentPlanIndividual()
    {
        $criteria = ["id" => 1];

        $container = $this->getContainer(
            $this->getDoctrine(),
            $this->getRepositoryIndividual(null, $criteria)
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->approveAction(new Request([], $criteria));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('"Plan septenal individual no existe"', $response->getContent());
    }

    public function testApproveActionOnExistentPlanIndividualWithWrongStatus()
    {
        $criteria = ["id" => 1];

        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);

        $planIndividual
            ->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('Modificando'));

        $container = $this->getContainer(
            $this->getDoctrine(),
            $this->getRepositoryIndividual($planIndividual, $criteria)
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->approveAction(new Request([], $criteria));

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('"Plan debe estar en espera por aprobaci\u00f3n"', $response->getContent());
    }

    public function testApproveActionOnExistentPlanIndividualWithRightStatus()
    {
        $criteria = ["id" => 1];

        $planIndividual = $this->createMock(PlanSeptenalIndividual::class);

        $planIndividual
            ->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('Esperando aprobación'));

        $planIndividual
            ->expects($this->once())
            ->method('approve');

        $container = $this->getContainer(
            $this->getDoctrine($planIndividual),
            $this->getRepositoryIndividual($planIndividual, $criteria)
        );

        $controller = new PlanSeptenalIndividualController();
        $controller->setContainer($container);

        $response = $controller->approveAction(new Request([], $criteria));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('"success"', $response->getContent());
    }

    private function getContainer($doctrine = null, $repositoryIndividual = null, $tokenStorage = null, $repositoryColectivo = null)
    {
        $container = $this->createMock(ContainerInterface::class);
        $step = 0;

        if (! is_null($doctrine)) {
            $container
                ->expects($this->at($step++))
                ->method('has')
                ->with('doctrine')
                ->will($this->returnValue(true));

            $container
                ->expects($this->at($step++))
                ->method('get')
                ->with('doctrine')
                ->will($this->returnValue($doctrine));
        }

        if (! is_null($repositoryColectivo)) {
            $container
                ->expects($this->at($step++))
                ->method('get')
                ->with('plan_septenal.plan_septenal_colectivo_repository')
                ->will($this->returnValue($repositoryColectivo));
        }

        if (! is_null($repositoryIndividual)) {
            $container
                ->expects($this->at($step++))
                ->method('get')
                ->with('plan_septenal.plan_septenal_individual_repository')
                ->will($this->returnValue($repositoryIndividual));

        }

        if (! is_null($tokenStorage)) {
            $container
                ->expects($this->at($step++))
                ->method('has')
                ->with('security.token_storage')
                ->will($this->returnValue(true));

            $container
                ->expects($this->at($step++))
                ->method('get')
                ->with('security.token_storage')
                ->will($this->returnValue($tokenStorage));
        }

        return $container;
    }

    private function getTokenStorage()
    {
        $usuario = new Usuario();
        $usuario->setPrimerNombre('tony')
            ->setPrimerApellido('stark')
            ->setPassword('1234');

        $token = new UsernamePasswordToken($usuario, '1234', 'default', array('ROLE_ADMINISTRADOR'));

        $tokenStorage = $this->getMockBuilder(TokenStorage::class)
                      ->setMethods(['getToken'])
                      ->getMock();
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token));

        return $tokenStorage;
    }

    private function getDoctrine($toPersist = null)
    {
        $doctrine = $this->createMock(ManagerRegistry::class);

        $entityManager = $this->createMock(ObjectManager::class);

        if (! is_null($toPersist)) {
            $entityManager
                ->expects($this->once())
                ->method('persist')
                ->with($toPersist);

            $entityManager
                ->expects($this->once())
                ->method('flush');
        }

        $doctrine
            ->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($entityManager));

        return $doctrine;
    }

    private function getRepositoryIndividual($result = null, $criteria = null)
    {
        $repo = $this->getMockBuilder(PlanSeptenalIndividualRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        if (is_null($criteria)) {
            $repo->expects($this->once())
                ->method('findOneBy')
                ->will($this->returnValue($result));
        } else {
            $repo->expects($this->once())
                ->method('findOneBy')
                ->with($criteria)
                ->will($this->returnValue($result));
        }
        return $repo;
    }

    private function getRepositoryColectivo($result = null)
    {
        $repo = $this->getMockBuilder(PlanSeptenalColectivoRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $repo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($result));

        return $repo;
    }
}
