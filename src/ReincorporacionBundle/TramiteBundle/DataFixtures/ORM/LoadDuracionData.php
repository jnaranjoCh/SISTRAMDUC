<?php

namespace TramiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\Duracion;

class LoadDuracionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $duracion_trimestre = new Duracion();
        $duracion_trimestre->setValor('3');
        $duracion_trimestre->setDescripcion('Trimestre');

        $manager->persist($duracion_trimestre);
        $manager->flush();

        $duracion_semestre = new Duracion();
        $duracion_semestre->setValor('6');
        $duracion_semestre->setDescripcion('Semestre');

        $manager->persist($duracion_semestre);
        $manager->flush();

        $duracion_anual = new Duracion();
        $duracion_anual->setValor('12');
        $duracion_anual->setDescripcion('Anual');

        $manager->persist($duracion_anual);
        $manager->flush();

        $duracion_otro = new Duracion();
        $duracion_otro->setValor('Definido por el administrador');
        $duracion_otro->setDescripcion('Otro');

        $manager->persist($duracion_otro);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}