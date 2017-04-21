<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use PlanSeptenalBundle\Entity\PlanSeptenalColectivo;
use PlanSeptenalBundle\Repository\PlanSeptenalIndividualRepository;
use PlanSeptenalBundle\Controller\PlanSeptenalColectivoController;

use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\Departamento;

class PlanSeptenalColectivoControllerUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOnSuccess()
    {
        $planColectivo = $this->getMockBuilder(PlanSeptenalColectivo::class)
            ->setMethods(['toArray'])
            ->disableOriginalConstructor()
            ->getMock();

        $planColectivo
            ->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(['array_representation']));

        $repo = $this->getMockBuilder(PlanSeptenalColectivoRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $repo->expects($this->once())
            ->method('findOneBy')
            ->with(['departamento' => 1, 'inicio' => 2010])
            ->will($this->returnValue($planColectivo));

        $container = $this->getContainer(
            $this->getDoctrine(),
            $repo,
            $this->getTokenStorage()
        );

        $controller = new PlanSeptenalColectivoController();
        $controller->setContainer($container);

        $response = $controller->getAction(new Request(['departamento' => 1, 'inicio' => 2010]));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["array_representation"]', $response->getContent());
    }

    private function getContainer($doctrine = null, $repositoryColectivo = null, $tokenStorage = null)
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

    private function getTokenStorage()
    {
        $departamento = $this->getMockBuilder(Departamento::class)
            ->setMethods(['getId'])
            ->getMock();

        $departamento->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $usuario = new Usuario();
        $usuario->setPrimerNombre('tony')
            ->setPrimerApellido('stark')
            ->setPassword('1234')
            ->setDepartamento($departamento);

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
}
