<?php

namespace TramiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\TipoDocumento;

class LoadTipoDocumentoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tipo_direccion = new TipoDocumento();
        $tipo_direccion->setId(1);
        $tipo_direccion->setNombre('Informe Direccion Asuntos Profesorales');

        $manager->persist($tipo_direccion);
        $manager->flush();

        $tipo_consejo_facultad = new TipoDocumento();
        $tipo_consejo_facultad->setId(2);
        $tipo_consejo_facultad->setNombre('Informe Consejo de Facultad');

        $manager->persist($tipo_consejo_facultad);
        $manager->flush();

        $tipo_consejo_departamento = new TipoDocumento();
        $tipo_consejo_departamento->setId(3);
        $tipo_consejo_departamento->setNombre('Informe Consejo de Departamento');

        $manager->persist($tipo_consejo_departamento);
        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }
}