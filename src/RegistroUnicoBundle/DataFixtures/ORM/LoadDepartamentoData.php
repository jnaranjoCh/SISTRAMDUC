<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\Departamento;

class LoadDepartamentoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $computacion = new Departamento();
        $computacion->setDescription('Computación');
        $computacion->setFacultadId($this->getReference('facyt-facultad')->getId());

        /*$tony = $this->getReference('tony-user');
        $tony->setDepartamento($computacion);*/

        $manager->persist($computacion);
        //$manager->persist($tony);
        $manager->flush();
    }

    public function getOrder()
    {
        return 11;
    }
}
