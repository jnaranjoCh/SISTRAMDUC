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

        $estado_enviada = new Estado();
        $estado_enviada->setNombre('Enviada');
        $estado_enviada->setDescripcion('Su solicitud ha sido enviada al Consejo de Facultad.');

        $manager->persist($estado_enviada);
        $manager->flush();
        
        $estado_enProceso = new Estado();
        $estado_enProceso->setNombre('En Proceso');
        $estado_enProceso->setDescripcion('Su solicitud está en proceso.');

        $manager->persist($estado_enProceso);
        $manager->flush();
        
        $estado_finalizada = new Estado();
        $estado_finalizada->setNombre('Finalizada');
        $estado_finalizada->setDescripcion('Su solicitud finalizó.');

        $manager->persist($estado_finalizada);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}