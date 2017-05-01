<?php

namespace TramiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\Estado;

class LoadEstadoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $estado_pendiente = new Estado();
        $estado_pendiente->setNombre('Pendiente');
        $estado_pendiente->setDescripcion('Solicitud pendiente por evaluación.');

        $manager->persist($estado_pendiente);
        $manager->flush();

        $estado_negada = new Estado();
        $estado_negada->setNombre('Negada');
        $estado_negada->setDescripcion('Su solicitud ha sido negada.');

        $manager->persist($estado_negada);
        $manager->flush();

        $estado_aprobada = new Estado();
        $estado_aprobada->setNombre('Aprobada');
        $estado_aprobada->setDescripcion('Su solicitud ha sido aprobada.');

        $manager->persist($estado_aprobada);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}