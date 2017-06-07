<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use AppBundle\Entity\Rol;
use AppBundle\Entity\Permiso;

class LoadRolData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $profesor_rol = new Rol();
        $profesor_rol->setNombre('Profesor');
        $profesor_rol->setDescription('Profesor de la Universidad de Carabobo');

        $profesor_rol->addPermiso(new Permiso("comisión de servicio - consultar"));
        $profesor_rol->addPermiso(new Permiso("comisión de servicio - estado de solicitud"));

        $manager->persist($profesor_rol);
        $manager->flush();
        $this->addReference('profesor-rol', $profesor_rol);

        $asuntos_profesorales_rol = new Rol();
        $asuntos_profesorales_rol->setNombre("Asuntos Profesorales");
        $asuntos_profesorales_rol->setDescription("Asuntos Profesorales");

        $asuntos_profesorales_rol->addPermiso(new Permiso("comisión de servicio - ver solicitudes AAPP"));

        $manager->persist($asuntos_profesorales_rol);
        $manager->flush();
        $this->addReference('asuntos-profesorales-rol', $asuntos_profesorales_rol);

        $consejo_facultad_rol = new Rol();
        $consejo_facultad_rol->setNombre("Consejo de Facultad");
        $consejo_facultad_rol->setDescription("Consejo de Facultad");

        $consejo_facultad_rol->addPermiso(new Permiso("comisión de servicio - consejo - ver solicitud"));

        $manager->persist($consejo_facultad_rol);
        $manager->flush();
        $this->addReference('consejo-facultad-rol', $consejo_facultad_rol);

        $catedra_rol = new Rol();
        $catedra_rol->setNombre("Catedra");
        $catedra_rol->setDescription("Catedra");

        $catedra_rol->addPermiso(new Permiso("comisión de servicio - catedra - ver solicitud"));

        $manager->persist($catedra_rol);
        $manager->flush();
        $this->addReference('catedra-rol', $catedra_rol);

        $consejo_departamento_rol = new Rol();
        $consejo_departamento_rol->setNombre("Consejo de Departamento");
        $consejo_departamento_rol->setDescription("Consejo de Departamento");

        $consejo_departamento_rol->addPermiso(new Permiso("comisión de servicio - departamento - ver solicitud"));

        $manager->persist($consejo_departamento_rol);
        $manager->flush();
        $this->addReference('consejo-departamento-rol', $consejo_departamento_rol);

        $administrador_rol = new Rol();
        $administrador_rol->setNombre('Administrador');
        $administrador_rol->setDescription('Rol para administrar el sistema SISTRAMDUC');

        $administrador_rol->addPermiso(new Permiso("comisión de servicio - consultar"));

        $manager->persist($administrador_rol);
        $manager->flush();
        $this->addReference('administrador-rol', $administrador_rol);
    }

    public function getOrder()
    {
        return 1;
    }
}
