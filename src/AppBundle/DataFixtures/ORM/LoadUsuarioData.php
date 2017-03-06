<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Usuario;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
        $user = new Usuario();
        $user->setCedula('1234');
        $user->setPrimerNombre('Anthony');
        $user->setSegundoNombre('Edward');
        $user->setPrimerApellido('Stark');
        $user->setSegundoApellido(' ');
        $user->setNacionalidad('_');
        $user->setCorreo('tony@stark.com');
        $user->setTelefono(0);
        $user->setRif(0);
        $user->setActivo(true);
        $user->setDireccion('Malibu');
        $user->setEstatusId(1);

        // the 'security.password_encoder' service requires Symfony 2.6 or higher
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, '1234');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
