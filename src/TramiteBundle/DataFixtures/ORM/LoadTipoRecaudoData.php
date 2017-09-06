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
        
        $tipo_constancia_estudio = new TipoRecaudo();
        $tipo_constancia_estudio->setNombre('Constancia de estudio o de inscripción');

        $manager->persist($tipo_constancia_estudio);
        $manager->flush();

        $tipo_carta_solteria = new TipoRecaudo();
        $tipo_carta_solteria->setNombre('Carta de soltería');

        $manager->persist($tipo_carta_solteria);
        $manager->flush();
        
        $tipo_carta_expensas = new TipoRecaudo();
        $tipo_carta_expensas->setNombre('Carta de expensas');

        $manager->persist($tipo_carta_expensas);
        $manager->flush();
        
        $tipo_certificado_conapdis = new TipoRecaudo();
        $tipo_certificado_conapdis->setNombre('Certificado de personas con discapacidad CONAPDIS');

        $manager->persist($tipo_certificado_conapdis);
        $manager->flush();
        
        $tipo_calificacion_conapdis = new TipoRecaudo();
        $tipo_calificacion_conapdis->setNombre('Calificación de personas con discapacidad CONAPDIS');

        $manager->persist($tipo_calificacion_conapdis);
        $manager->flush();
        
        $tipo_informe_medico = new TipoRecaudo();
        $tipo_informe_medico->setNombre('Informe médico');

        $manager->persist($tipo_informe_medico);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}