<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ConcursosBundle\Entity\Aspirante;

class LoadAspiranteData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $barbara = new Aspirante();
        $barbara->setCedula('20950179');
        $barbara->setPrimerNombre('Barbara');
        $barbara->setSegundoNombre('Alejandra');
        $barbara->setPrimerApellido('Lozada');
        $barbara->setSegundoApellido('Silva');
        $barbara->setTelefono(04127777347);
        $barbara->setCorreo('barbara@sti.com');
        $barbara->setComunicacionEscritaUrl(' ');
        $barbara->setCartaConductaUrl(' ');
        $barbara->setReporteNotaUrl(' ');
        $barbara->setPromedioAcademico(18);
        $barbara->setNotaIntento1(15);
        
        $manager->persist($barbara);
        $manager->flush();

        $this->addReference('barbara-aspirante', $barbara);
    }

    public function getOrder()
    {
        return 1;
    }
}
