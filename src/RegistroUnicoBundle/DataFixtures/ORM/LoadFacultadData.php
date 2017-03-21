<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\Facultad;

class LoadFacultadData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $facyt = new Facultad();
        $facyt->setDescription('FACYT');

        $manager->persist($facyt);
        $manager->flush();

        $this->setReference('facyt-facultad', $facyt);
    }

    public function getOrder()
    {
        return 10;
    }
}
