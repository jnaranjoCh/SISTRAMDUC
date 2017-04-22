<?php

namespace TramiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use TramiteBundle\Entity\TipoTramite;

class LoadTipoTramiteData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tipo_registro = new TipoTramite();
        $tipo_registro->setNombre('Registro Unico');
        $tipo_registro->setDescripcion('');
        $tipo_registro->setDuracion('');

        $manager->persist($tipo_registro);
        $manager->flush();

        $tipo_plan = new TipoTramite();
        $tipo_plan->setNombre('Plan Septenal');
        $tipo_plan->setDescripcion('');
        $tipo_plan->setDuracion('');

        $manager->persist($tipo_plan);
        $manager->flush();

        $tipo_reincorporacion = new TipoTramite();
        $tipo_reincorporacion->setNombre('Reincorporacion');
        $tipo_reincorporacion->setDescripcion('');
        $tipo_reincorporacion->setDuracion('');

        $manager->persist($tipo_reincorporacion);
        $manager->flush();

        $tipo_clausulas = new TipoTramite();
        $tipo_clausulas->setNombre('Clausulas Contractuales');
        $tipo_clausulas->setDescripcion('');
        $tipo_clausulas->setDuracion('');

        $manager->persist($tipo_clausulas);
        $manager->flush();

        $tipo_preparadores = new TipoTramite();
        $tipo_preparadores->setNombre('Preparadores');
        $tipo_preparadores->setDescripcion('');
        $tipo_preparadores->setDuracion('');

        $manager->persist($tipo_preparadores);
        $manager->flush();

        $tipo_comision = new TipoTramite();
        $tipo_comision->setNombre('Comision de Servicio');
        $tipo_comision->setDescripcion('');
        $tipo_comision->setDuracion('30 dias');

        $manager->persist($tipo_comision);
        $manager->flush();

        $tipo_jubilacion = new TipoTramite();
        $tipo_jubilacion->setNombre('Jubilacion');
        $tipo_jubilacion->setDescripcion('');
        $tipo_jubilacion->setDuracion('30 dias');

        $manager->persist($tipo_jubilacion);
        $manager->flush();

        $tipo_concurso = new TipoTramite();
        $tipo_concurso->setNombre('Concurso de Oposicion');
        $tipo_concurso->setDescripcion('');
        $tipo_concurso->setDuracion('');

        $manager->persist($tipo_concurso);
        $manager->flush();        
    }

    public function getOrder()
    {
        return 1;
    }
}