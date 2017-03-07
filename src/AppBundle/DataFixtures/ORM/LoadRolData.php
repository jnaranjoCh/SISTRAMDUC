<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use AppBundle\Entity\Rol;

class LoadRolData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $profesor_rol = new Rol();
        $profesor_rol->setNombre('Profesor');
        $profesor_rol->setDescription('Profesor de la Universidad de Carabobo');

        $manager->persist($profesor_rol);
        $manager->flush();

        $this->addReference('profesor-rol', $profesor_rol);
    }

    public function getOrder()
    {
        return 1;
    }
}
