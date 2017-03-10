<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\Estatus;

class LoadEstatusData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Iniciciando","En proceso","Culminado"];
        
        foreach($array as $val){
            $estatus = new Estatus();
            $estatus->setDescription($val);
            $manager->persist($estatus);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
