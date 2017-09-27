<?php

namespace ReincorporacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RegistroUnicoBundle\Entity\Cargo as cargo;
use RegistroUnicoBundle\Entity\Estatus as estatus;
use RegistroUnicoBundle\Entity\Nivel as nivel;

class CargarDatosController extends Controller
{
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
}
