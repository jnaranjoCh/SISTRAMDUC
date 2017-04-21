<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\TipoRecaudo;

class LoadTipoRecaudoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Cedula","RIF","Acta de nacimiento"];

        foreach($array as $val){
            $tipo_recaudo = new TipoRecaudo();
            $tipo_recaudo->setNombre($val);
            $manager->persist($tipo_recaudo);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}