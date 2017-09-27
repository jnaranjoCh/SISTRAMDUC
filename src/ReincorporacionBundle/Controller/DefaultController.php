<?php

namespace ReincorporacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use RegistroUnicoBundle\Entity\Cargo as cargo;
use RegistroUnicoBundle\Entity\Estatus as estatus;
use RegistroUnicoBundle\Entity\Nivel as nivel;
use RegistroUnicoBundle\Entity\TipoRegistro as tipo_registro;
use RegistroUnicoBundle\Entity\Registro as registro;

class DefaultController extends Controller
{
    //-------------------------------
    // Operaciones con datos
    //-------------------------------

    /**
     * Retorna todos los cargos para cargarlos en el select
     *
     * @return array
     */
    public function getAllCargos() {
        $data = $this->getDoctrine()
            ->getRepository( cargo::class)
            ->findAll();

        $cargos = [];
        foreach ($data as $d) {
            $cargos[] = $d->getDescription();
        }

        return $cargos;
    }

    /**
     * Retorna todos los estatuses para cargarlos en el select
     *
     * @return array
     */
    public function getAllEstatuses() {
        $data = $this->getDoctrine()
            ->getRepository(estatus::class)
            ->findAll();

        $estatuses = [];
        foreach ($data as $d) {
            $estatuses[] = $d->getDescription();
        }

        return $estatuses;
    }

    /**
     * Retorna todos los niveles de estudio para cargarlos en el select
     *
     * @return array
     */
    public function getAllNivelesEstudio() {
        $data = $this->getDoctrine()
            ->getRepository(nivel::class)
            ->findAll();

        $niveles = [];
        foreach ($data as $d) {
            $niveles[] = $d->getDescription();
        }

        return $niveles;
    }

    /**
     * Retorna todos los tipos de registro existentes para crear una nueva experiencia
     *
     * @return array
     */
    public function getAllTiposRegistro() {
        $data = $this->getDoctrine()
            ->getRepository(tipo_registro::class)
            ->findAll();

        $tipo_registro = [];
        foreach ($data as $d) {
            $tipo_registro[] = $d->getDescription();
        }

        return $tipo_registro;
    }

    /**
     * Retorna todos los ids de los registros del usuario a los que puede asignarse un participante
     * (Todos menos Becas, Premios, Distinciones)
     *
     * @return array
     */
    public function getRegistrosAsignablesAParticipantes() {
        $em = $this->getDoctrine()->getRepository('RegistroUnicoBundle:Registro');
        $q = $em->createQueryBuilder('reg')
            ->where('reg.tipo_registro not in (8,9,10)')
            ->getQuery();
        $data = $q->getResult();

        $reg_ids = [];
        foreach ($data as $d) {
            $reg_ids[] = $d->getTipoRegitroId();
        }
    }

    /**
     * Retorna los ids de los registros del usuario referentes a las publicaciones
     *
     * @return array
     */
    public function getRegistrosPublicaciones() {
        $em = $this->getDoctrine()->getRepository('RegistroUnicoBundle:Registro');
        $q = $em->createQueryBuilder('reg')
            ->where('reg.tipo_registro not in (6,7,8,9,10)')
            ->getQuery();
        $data = $q->getResult();

        $reg_ids = [];
        foreach ($data as $d) {
            $reg_ids[] = $d->getTipoRegitroId();
        }

        return $reg_ids;
    }

    /**
     * Retorna todos los cargos ejercidos por el usuario
     *
     * @return array
     */
    public function getRegistrosUsuario() {
        $em = $this->getDoctrine()->getRepository('RegistroUnicoBundle:Registro');
        $q = $em->createQueryBuilder('reg')
            ->getQuery();
        $data = $q->getResult();

        $registros = [];
        foreach ($data as $d) {
            $reg['id'] = $d->getId();
            $reg['tipo_registro_id'] = $d->getTipo()->getId();
            $reg['tipo_registro'] = $d->getTipo()->getDescription();
            $reg['nivel_id'] = $d->getNivel()->getId();
            $reg['nivel'] = $d->getNivel()->getDescription();
            $reg['estatus_id'] = $d->getEstatusId();
            $reg['anio'] = $d->getAño();
            $reg['es_valido'] = $d->getIsValidate();
            $reg['locacion'] = $d->getInstitucionEmpresaCasaeditorial();
            $reg['titulo_obtenido'] = $d->getTituloObtenido();
            $reg['ciudad_pais'] = $d->getCiudadPais();
            $reg['congreso'] = $d->getCongreso();
            $reg['descripcion'] = $d->getDescription();

            $registros[] = $reg;
        }

        echo '<pre>';
        print_r($registros);
        echo '</pre>';

    }

    //---------------------------
    // Carga de vistas
    //---------------------------

    /**
     * @Route("/reincorporacion-docente", name="reincorporacion")
     */
    public function mostrarInicioDeSolicitud()
    {
        return $this->render('ReincorporacionBundle::inicio-solicitud.html.twig');
    }

    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum", name="actualizar-curriculum")
     */ 
    public function mostrarActualizarCurriculum()
    {

        return $this->render('ReincorporacionBundle::actualizar-curriculum.html.twig');
    }

    /**
     * @Route("/reincorporacion")
     */
    
    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum/nueva-entrada", name="nueva-entrada-curriculum")
     */ 
    public function mostrarCrearEntradaEnCurriculum()
    {
        $cargos = $this->getAllCargos();
        $estatuses = $this->getAllEstatuses();
        $niveles = $this->getAllNivelesEstudio();
        $tipos_registro = $this->getAllTiposRegistro();
        $reg_ids_partic = $this->getRegistrosAsignablesAParticipantes();
        $reg_ids_public = $this->getRegistrosPublicaciones();
        $registros = $this->getRegistrosUsuario();

        return $this->render('ReincorporacionBundle::nueva-entrada-curriculumt.html.twig',
            array(
                'cargos' => $cargos,
                'estatuses' => $estatuses,
                'niveles' => $niveles,
                'tipos_registro' => $tipos_registro,
                'reg_ids_partic' => $reg_ids_partic,
                'reg_ids_public' => $reg_ids_public,
                'registros' => $registros
            )
        );
    }
    

    /**
     * @Route("/reincorporacion-docente/subir-recaudos", name="subir-recaudos")
     */ 
    public function mostrarSubirRecaudos()
    {
        return $this->render('ReincorporacionBundle::subir-recaudos.html.twig');
    }

    /**
     * @Route("/reincorporacion-docente/verificar-datos", name="verificar-datos")
     */ 
    public function mostrarVerificarDatos()
    {
        return $this->render('ReincorporacionBundle::verificar-datos.html.twig');
    }
}
