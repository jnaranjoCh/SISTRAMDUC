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

        $tipo_oficio_jubilacion = new TipoRecaudo();
        $tipo_oficio_jubilacion->setNombre('Oficio de Solicitud de Jubilación');

        $manager->persist($tipo_oficio_jubilacion);
        $manager->flush();

        $tipo_constancia_jubilacion = new TipoRecaudo();
        $tipo_constancia_jubilacion->setNombre('Constancia para efecto de Jubilación');

        $manager->persist($tipo_constancia_jubilacion);
        $manager->flush();

        $tipo_constancia_antecedente = new TipoRecaudo();
        $tipo_constancia_antecedente->setNombre('Constancia de Antecedentes de servicios del ente de la administración pública donde laboró');

        $manager->persist($tipo_constancia_antecedente);
        $manager->flush();

        $tipo_constancia_preparador = new TipoRecaudo();
        $tipo_constancia_preparador->setNombre('Constancia de cumplimiento de labores como preparador');

        $manager->persist($tipo_constancia_preparador);
        $manager->flush();

        $tipo_recibo_pago = new TipoRecaudo();
        $tipo_recibo_pago->setNombre('Recibo de Pago');

        $manager->persist($tipo_recibo_pago);
        $manager->flush();

        $tipo_designacion_como_docente = new TipoRecaudo();
        $tipo_designacion_como_docente->setNombre('Designación como docente');

        $manager->persist($tipo_designacion_como_docente);
        $manager->flush();
        
        $tipo_oficio_ubicacion = new TipoRecaudo();
        $tipo_oficio_ubicacion->setNombre('Oficio de la ubicación');

        $manager->persist($tipo_oficio_ubicacion);
        $manager->flush();

        $tipo_ultimo_ascenso = new TipoRecaudo();
        $tipo_ultimo_ascenso->setNombre('Oficio del último ascenso');

        $manager->persist($tipo_ultimo_ascenso);
        $manager->flush();

        $tipo_aceptacion_renuncia = new TipoRecaudo();
        $tipo_aceptacion_renuncia->setNombre('Oficio de la aceptación de la renuncia');

        $manager->persist($tipo_aceptacion_renuncia);
        $manager->flush();

        $tipo_fondo_titulo = new TipoRecaudo();
        $tipo_fondo_titulo->setNombre('Fondo negro del título');

        $manager->persist($tipo_fondo_titulo);
        $manager->flush();

        $tipo_declaracion_jurada_cargos = new TipoRecaudo();
        $tipo_declaracion_jurada_cargos->setNombre('Declaración jurada de cargos ejercidos durante el período fuera de la Universidad de Carabobo');

        $manager->persist($tipo_declaracion_jurada_cargos);
        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }
}