<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\Usuario;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $tony = new Usuario();
        $tony->setCedula('1234');
        $tony->setPrimerNombre('Anthony');
        $tony->setSegundoNombre('Edward');
        $tony->setPrimerApellido('Stark');
        $tony->setSegundoApellido(' ');
        $tony->setNacionalidad('_');
        $tony->setCorreo('tony@stark.com');
        $tony->setTelefono(0);
        $tony->setRif(0);
        $tony->setActivo(true);
        $tony->setDireccion('Malibu');
        $tony->setEstatusId(1);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($tony, '1234');
        $tony->setPassword($password);

        $tony->addRol($this->getReference('profesor-rol'));

        $manager->persist($tony);
        $manager->flush();

        $this->addReference('tony-user', $tony);
    }

    public function getOrder()
    {
        return 2;
    }
}
