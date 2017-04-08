<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use RegistroUnicoBundle\Entity\TipoRegistro;

class LoadTipoRegistroData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Articulo publicado","Tutoria de pasantias","Tutoria de servicio comunitario","Asistencia a congresos","Estudio"];
        
        foreach($array as $val){
            $tiporegistro = new TipoRegistro();
            $tiporegistro->setDescription($val);
            $manager->persist($tiporegistro);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
