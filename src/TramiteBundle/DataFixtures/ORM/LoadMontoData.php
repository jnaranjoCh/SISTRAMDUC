<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\Monto;

class LoadMontoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $description = ["Pre-escolar","Primaria","Secundaria","Universitaria","Mensualidad discapacidad","Mensualidad prima por hijos"];
        $montos       = [20000.00,30000.00,40000.00,50000.00,60000.00,20000.00];
        $i = 0;
        
        foreach($description as $val){
            $monto = new Monto();
            $monto->setDescription($val);
            $monto->setAmount($montos[$i]);
            $manager->persist($monto);
            $manager->flush();
            $this->addReference($val.'-monto', $monto);
            $i++;
        }
    }

    public function getOrder()
    {
        return 1;
    }
}