<?php

namespace DescargaHorariaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use DescargaHorariaBundle\Entity\CargoDesignacion;

class LoadCargoDesignacionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = ["Comisionado(a) de la Rector(a) Núcleo Aragua",
                    "Consultor(a) Jurídico(a) (CJUC)",
                    "Director(a) de Recursos Humanos Central (DCRRHH)",
                    "Director(a) de Mantenimiento, Ambiente e Infraestructura Central (DCMAI)",
                    "Director(a) de Transporte Central (DCTUC)",
                    "Director(a) General de Organización Institucional (DiGOI-UC)",
                    "Coordinador(a) de Alianzas Estrégicas de la Rectoría Despacho",
                    "Asesor(a) Financiero(a) de la Rectoría Despacho",
                    "Coordinador(a) de Gestión Académica de la Rectoría Despacho",
                    "Coordinador(a) de Gestión Administrativo(a) de la Rectoría Despacho",
                    "Coordinador(a) Ejecutivo(a) de la Rectoría Despacho",
                    "Director(a) de Cultura Central (DCCUC)",
                    "Director(a) de Deportes (DCDUC)",
                    "Director(a) de Desarrollo Estudiantil Central  (DCDE)",
                    "Director(a) de Extensión y Servicios a la Comunidad Central  (DCESCO)",
                    "Director(a) de Medios Electrónicos y Telemática (DIMETEL)",
                    "Director(a) de Medios y Publicaciones (DMP)",
                    "Director(a) de Tecnología Avanzada (DTA)",
                    "Coordinador(a) General de Desarrollo Estudiantil-Núcleo Aragua",
                    "Director(a) de Capellania",
                    "Director(a) de Relaciones Interinstitucionales Central (DCRII)",
                    "Coordinador(a) General de Transporte-Núcleo Aragua",
                    "Coordinador(a) General de Consultoría Jurídica (CJUC)",
                    "SubDirector(a) de Recursos Humanos Central",
                    "Coordinador(a) de Asuntos Jurídicos (CJUC)",
                    "Comisionado(a) de la Rectora Núcleo Cojedes",
                    "Comisionado(a) de la Rector(a) Extensión Puerto Cabello",
                    "Coordinador(a) General de Salud-Núcleo Aragua",
                    "Coordinador(a) de Ceremonial y Protocolo (CCP)",
                    "Director(a) de Centro de Estudios sobre el Problema de las Drogas (CEPRODUC)",
                    "Coordinador(a) General de la Dirección de Cultura",
                    "Coordinador(a) General Deportes Central (DCDUC)",
                    "Coordinador(a) de Medios Electrónicos Dedicados (MED-DIMETEL)",
                    "Coordinador(a) de la Radiodifusora Universitaria (FMUC-DIMETEL)",
                    "Coordinador(a) de la Red Dorsal Integrada de la Universidad de Carabobo (REDIUC-DIMETEL)",
                    "Coordinador(a) de la Galería Braulio Salazar",
                    "Coordinador(a) General de Relaciones Interinstitucionales-Núcleo Aragua",
                    "Coordinador(a) General de Cultura-Núcleo Aragua",
                    "Coordinador(a) General de Deportes-Núcleo Aragua",
                    "Coordinador(a) General de Extensión y Servicios a la Comunidad-Núcleo Aragua",
                    "Coordinador(a) General de Promoción de Extensión, Servicios y Relación con el Entorno (DCESCO)",
                    "Coordinador(a) General de Acción Social Comunitaria (DCESCO)",
                    "Coordinador(a) General de Planificación y Desarrollo de Extensión y Servicios a la Comunidad (DCESCO)",
                    "Coordinador(a) de la Fundación de Asistencia Médica Hospitalaria para Estudiantes de Educación Superior (FAMES) (DCDE)",
                    "Coordinador(a) de Bienestar Estudiantil (DCDE)",
                    "Coordinador(a) de Servicios Médicos Estudiantiles (DDE)",
                    "Coordinador(a) de Escuela de Futbol",
                    "Coordinador(a) del Centro de Interpretación Histórica, Cultural y Patrimonial",
                    "Coordinador(a) de Gestión de Recursos Humanos Central (DCRRHH)",
                    "Coordinador(a) de Relaciones Laborales (DCRRHH)",
                    "Coordinador(a) Aula Magna",
                    "Coordinador(a) de la Oficina de Prestaciones Sociales (DRRHH)",
                    "Jefe(a) de Atención al Ciudadano (OAC)",
                    "Coordinador(a) Académico(a) de la Cátedra Rectoral en Educación en Valores",
                    "Coordinador(a) de Implementación y Mantenimiento de la UC  (DIMETEL)",
                    "Coordinador(a) de Programación Radiodifusora Universitaria (FMUC-DIMETEL)",
                    "Coordinador(a) de la Televisora Universitaria (UCTV-DIMETEL)",
                    "Asistente Secretarial de Despacho Rectoral",
                    "Representante de la Rector(a) en Gestión Institucional",
                    "Coordinador(a) General de Tecnología Interactiva (DTA)",
                    "Coordinador(a) de los Espacios Alternativos de la Rectoría",
                    "Coordinador(a) Teatro “Doctor Alfredo Celis Pérez”",
                    "Coordinador(a) General de Medios y Publicaciones (DMP)",
                    "Coordinador(a) de Gestión y Estadísticas de Servicios Estudiantiles (DCDE)",
                    "Coordinador(a) de Bienestar Estudiantil-Núcleo Aragua (DCDE)",
                    "Coordinador(a) de Información y Gestión Docente (DCRRHH)",
                    "Director(a) de Comedores Universitarios (DICU)",
                    "Coordinador(a) de Procesos Administrativos, Financieros y Presupuestarios de la Rectoría -Despacho",
                    "Coordinador(a) de Gestión Estratégica (DIGOI)",
                    "Coordinadora de Relaciones e Intercambio Pública de la Rectoría-Despacho",
                    "Coordinador(a) de Gestión Organizacional (DIGOI)",
                    "Coordinador(a) de Gestión de Procesos (DIGOI)",
                    "Coordinador(a) de Comunicación (DCMP)",
                    "Coordinador De Proyectos y Productos Editoriales Medios (DCMP)",
                    "Coordinador(a) General de Transporte Central",
                    "Coordinador(a) Mantenimiento Ambiente e Infrestructura Central (DCMAI)",
                    "Coordinador(a) de Cine UC",
                    "Director(a) General de Asuntos Profesorales (DGAP)",
                    "Asistente del Vicerrector Académico",
                    "Asistente de Gestión Académica(VRAC)",
                    "Director(a) de Docencia y Desarrollo Curricular Central (DCDDC)",
                    "Director(a) de Biblioteca Central (DBC)",
                    "Asistente Ejecutivo de Desarrollo, Científico y Humanístico (CDCH)",
                    "Asistente de Postgrado (DCP)",
                    "Asistente Secretarial del Vicerrector Académico",
                    "Director(a) de Administración y Finanzas Central (DCAyF)",
                    "Director(a) de Informática (DIUC)",
                    "Director(a) de Planificación y Presupuesto Central (DCPP)",
                    "Director(a) del Plan de Hospitalización, Cirugía y Maternidad (HCM)",
                    "Director(a) de Salud Integral de la Universidad de Carabobo (DISIUC)",
                    "Coordinador Médico de Hospitalización, Cirugía y Maternidad (HCM)",
                    "Coordinador General del Vicerrectorado Administrativo (VRAD)",
                    "Coordinador(a) General de Atención Médica Integral (UAMI-DISIUC)",
                    "Coordinador(a) General de Servicio de Seguridad y Salud en el Trabajo (SSST-DISIUC)",
                    "Gerente Médico de Hospitalización, Cirugía y Maternidad (HCM)",
                    "Coordinador(a) General de Informática (DIUC)",
                    "SubDirector(a) de Planificación y Presupuesto Central (DCPP)",
                    "SubDirector(a) de Administración y Finanzas Central (DAyFC)",
                    "Coordinador(a) de Tributos Central (DCAyF)",
                    "Coordinador(a) de Compras y Suministros Central DCAyF)",
                    "Coordinador(a) de Gestión de Sistemas Administrativos (VRAD)",
                    "Asistente Secretarial de Vicerrector(a) Administrativo",
                    "Coordinador(a) de Gestión y Resguardo de la Producción (DIUC)",
                    "Coordinador(a) para el Desarrollo de Software (DIUC)",
                    "Coordinador(a) de Gestión de Cálculo Masivo y Mantenimiento de Sistemas (DIUC)",
                    "Coordinador(a) de Gestión para la Aplicación de Nuevas Tecnologías (DIUC)",
                    "Coordinador(a) de Soporte Técnico y Atención al Usuario (DIUC)",
                    "Coordinador(a) de Planificación Central (DCPP)",
                    "Coordinador(a) de Formulación y Reformulación Presupuetaria Central (DCPP)",
                    "Coordinador(a) de Ejecución Presupuestaria Central (DCPP)",
                    "Coordinador(a) Financiero (DAyFC)",
                    "Coordinador(a) de Gestión y Procedimientos Administrativos Central (DCAyF)",
                    "Coordinador(a) de Contrataciones Públicas y Control de Ingresos Central (DCAyF)",
                    "Coordinador(a) de Procesos Administrativos, Financieros y Presupuestarios del Vicerrectorado Administrativo (VRAD)",
                    "Director(a) de Asuntos Estudiantiles Central (DICAE)",
                    "Director(a) Ejecutivo(a) de la Secretaria del Consejo Universitario (DISCU)",
                    "Coordinador(a) General de Secretaria UC",
                    "Asistente de Secretario(a)",
                    "SubDirector(a) de Información, Orientación y Admisión (DICAE)",
                    "Coordinador(a) Académico(a)-Secretaría",
                    "Asistente de Relaciones Públicas de Secretaría Despacho",
                    "Coordinador(a) de la Oficina de Secretaría-Núcleo Aragua",
                    "Coordinador(a) de Egreso Estudiantil (DICAE)",
                    "Coordinador(a) de Prosecución Académico(a) (DICAE)",
                    "Coordinador(a) del Programa Regional de Información y Orientación Vocacional (PRIOV-DIGAE)",
                    "Asistente Secretarial de Despacho del Secretario(a)",
                    "Asistente Secretarial de la Dirección de Secretaria del Consejo Universitario (DISCU)",
                    "Coordinador(a) de Control Posterior (DAIUC)",
                    "Coordinador(a) de Determinación de Responsabilidades (DAIUC)",
                    "Coordinador(a) de Potestad Investigativa (DAIUC)",
                    "Director(a) Ejecutivo(a) de Desarrollo, Científico y Humanístico (CDCH)",
                    "Director(a) De Postgrado Central (DCP)",
                    "Director(a) de Auditora Interna (DAIUC)",
                    "Coordinador(a) Técnico(a) de Auditoria Académica",
                    "Presidente(a) de la Comisión Electoral",
                    "Presidente(a) de Consejo de Apelaciones",
                    "Secretario(a) de la Comisión Electoral",
                    "Secretario(a) del Consejo de Apelaciones",
                    "Coordinador(a) de Gestión de Salud, Higiene y Seguridad (VRAD)",
                    "Director(a) de Prevención de Incendios, Protección y Seguridad de la UC (PIPSUC)",
                    "Coordinador(a) General de Seguridad Integral (PIPSUC)",
                    "Coordinador(a) General de Cuerpo de Bomberos de Universidad de Carabobo (1er. Comandante) (PIPSUC)",
                    "Coordinador(a) de Gestión de Operaciones del Cuerpo de Bomberos de la universidad de Carabobo (2do Comandante) (PIPSUC)",
                    "Inspector General de Prevención e Investigación de Incendios y de Otros Siniestros del Cuerpo de Bomberos de la Universidad de Carabobo (PIPSUC)",
                    "Coordinador(a) de Zona 01 de Seguridad (PIPSUC)",
                    "Coordinador(a) de Zona 02 de Seguridad (PIPSUC)",
                    "Coordinador(a) de Zona 03 de Seguridad (PIPSUC)",
                    "Coordinador(a) de Zona 04 de Seguridad (PIPSUC)",
                    "Coordinador(a) de Investigación, Prevención y Control de Pérdidas (PIPSUC)",
                    "Coordinador(a) de Comunicación, Seguridad Electrónica y Gestión Interna",
                    "Asistente Operativo de Zona 01 de Seguridad (PIPSUC) ",
                    "Asistente Operativo de Zona 02 de Seguridad (PIPSUC)",
                    "Asistente Operativo de Zona 03 de Seguridad (PIPSUC)",
                    "Asistente Operativo de Zona 04 de Seguridad (PIPSUC)",
                    "Asistente Operativo de Zona 05 de Seguridad (PIPSUC)"
                    ];
        
        
        
        foreach($array as $val){
            $cargo = new CargoDesignacion();
            $cargo->setNombre($val);
            //$cargo->setDescripcion($val);
            $cargo->setCreated(new \DateTime('10-09-2017'));
            
            $manager->persist($cargo);
            $manager->flush();
        }
        
    }
    
    public function getOrder()
    {
        return 1;
    }
    
}