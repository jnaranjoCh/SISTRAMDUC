<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ConcursosBundle\Entity\Jurado;

class LoadJuradoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Coordinador","Principal","Suplente"];
        
        foreach($array as $val){
            $jurado = new Jurado();
            $jurado->setNombre('Desiree');
            $jurado->setApellido('Delgado');
            $jurado->setAreaInvestigacion('Algoritmo');
            $jurado->setTipo($val);
            $jurado->setIdUsuarioAsigna(1);
            $jurado->setCedula('8980725');
            $manager->persist($jurado);
            $manager->flush();
        }
        
    }

    public function getOrder()
    {
        return 1;
    }
}
