<?php

namespace DescargaHorariaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use DescargaHorariaBundle\Entity\TipoComponente;

class LoadTipoComponenteData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Docencia","Investigacion","Extension y Servicio", "Gerencia Universitaria y Gremial", "Formacion Permanente y Desarrollo Personal"];
        
        foreach($array as $val){
            $componente = new TipoComponente();
            $componente->setName($val);
            $manager->persist($componente);
            $manager->flush();
        }
    }
    
    public function getOrder()
    {
        return 1;
    }
    
}