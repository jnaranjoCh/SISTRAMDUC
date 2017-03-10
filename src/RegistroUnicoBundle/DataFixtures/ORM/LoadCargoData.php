<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\Cargo;

class LoadCargoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Docente","Administrativo"];
        
        foreach($array as $val){
            $cargo = new Cargo();
            $cargo->setDescription($val);
            $manager->persist($cargo);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
