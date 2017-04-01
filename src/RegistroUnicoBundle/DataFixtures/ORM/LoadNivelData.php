<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\Nivel;

class LoadNivelData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Curso","PreGrado","PostGrado","Doctorado"];
        
        foreach($array as $val){
            $nivel = new Nivel();
            $nivel->setDescription($val);
            $manager->persist($nivel);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
