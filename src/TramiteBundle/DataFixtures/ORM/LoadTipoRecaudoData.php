<?php

namespace TramiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\TipoRecaudo;

class LoadTipoRecaudoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tipo_cedula = new TipoRecaudo();
        $tipo_cedula->setNombre('Cedula');

        $manager->persist($tipo_cedula);
        $manager->flush();

        $tipo_rif = new TipoRecaudo();
        $tipo_rif->setNombre('RIF');

        $manager->persist($tipo_rif);
        $manager->flush();

        $tipo_partida = new TipoRecaudo();
        $tipo_partida->setNombre('Partida de Nacimiento');

        $manager->persist($tipo_partida);
        $manager->flush();

        $tipo_oficio_comision = new TipoRecaudo();
        $tipo_oficio_comision->setNombre('Oficio de Solicitud de la Comisión de Servicio por parte del Beneficiario');

        $manager->persist($tipo_oficio_comision);
        $manager->flush();

        $tipo_designacion_comision = new TipoRecaudo();
        $tipo_designacion_comision->setNombre('Copia de la Designación del cargos en la Administración');

        $manager->persist($tipo_designacion_comision);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}