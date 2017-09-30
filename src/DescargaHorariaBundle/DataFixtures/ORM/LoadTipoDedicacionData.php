<?php

namespace DescargaHorariaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use DescargaHorariaBundle\Entity\TipoDedicacion;

class LoadTipoDedicacionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Dedicacion Exclusiva","Tiempo Completo","Medio Tiempo","Tiempo Convencional"];
        
        foreach($array as $val){
            $tipo = new TipoDedicacion();
            $tipo->setDescription($val);
            $manager->persist($tipo);
            $manager->flush();
        }
        
    }
    
    public function getOrder()
    {
        return 1;
    }
}