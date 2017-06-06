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
        $tony->setEdad(26);
        $tony->setSexo('M');
        $tony->setPrimerNombre('Anthony');
        $tony->setSegundoNombre('Edward');
        $tony->setPrimerApellido('Stark');
        $tony->setSegundoApellido(' ');
        $tony->setNacionalidad('Venezolano');
        $tony->setCorreo('tony@stark.com');
        $tony->setTelefono('04124556234');
        $tony->setRif('');
        $tony->setActivo(true);
        $tony->setIsRegister(true);
        $tony->setDireccion('Valencia');
        //$tony->setEstatusId(1);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($tony, '1234');
        $tony->setPassword($password);

        $tony->addRol($this->getReference('administrador-rol'));

        $manager->persist($tony);
        $manager->flush();

        $anto = new Usuario();
        $anto->setCedula('14038422');
        $anto->setEdad(49);
        $anto->setSexo('F');
        $anto->setPrimerNombre('Antonieta');
        $anto->setSegundoNombre('Carolina');
        $anto->setPrimerApellido('Lopez');
        $anto->setSegundoApellido('de la Vega');
        $anto->setNacionalidad('Española');
        $anto->setCorreo('anto@gmail.com');
        $anto->setTelefono('04146784523');
        $anto->setRif('');
        $anto->setActivo(true);
        $anto->setIsRegister(true);
        $anto->setDireccion('Caracas');
        //$anto->setEstatusId(1);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($anto, '14038422');
        $anto->setPassword($password);

        $anto->addRol($this->getReference('administrador-rol'));

        $manager->persist($anto);
        $manager->flush();

        $marcos = new Usuario();
        $marcos->setCedula('10567123');
        $marcos->setEdad(44);
        $marcos->setSexo('M');
        $marcos->setPrimerNombre('Marcos');
        $marcos->setSegundoNombre('Antonio');
        $marcos->setPrimerApellido('Rodriguez');
        $marcos->setSegundoApellido(' ');
        $marcos->setNacionalidad('Venezolano');
        $marcos->setCorreo('marcos@gmail.com');
        $marcos->setTelefono('04146987523');
        $marcos->setRif('');
        $marcos->setActivo(true);
        $marcos->setIsRegister(true);
        $marcos->setDireccion('Valencia');
        //$marcos->setEstatusId(1);

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($marcos, '14038422');
        $marcos->setPassword($password);

        $marcos->addRol($this->getReference('administrador-rol'));

        $manager->persist($marcos);
        $manager->flush();


        $this->addReference('tony-user', $marcos);
    }

    public function getOrder()
    {
        return 2;
    }
}
