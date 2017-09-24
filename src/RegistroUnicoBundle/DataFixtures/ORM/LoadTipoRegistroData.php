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
        $array = ["Articulo publicado","Proyectos","Tutoria de tesis","Tutoria de pasantias","Tutoria de servicio comunitario","Asistencia a Congresos/Seminarios","Sociedad Científica y Profesionales","Becas","Premios","Distinciones","Estudio"];
        
        foreach($array as $val){
            $tiporegistro = new TipoRegistro();
            $tiporegistro->setDescription($val);
            $manager->persist($tiporegistro);
            $manager->flush();
            $this->addReference($val.'-tipoRegistro', $tiporegistro);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
