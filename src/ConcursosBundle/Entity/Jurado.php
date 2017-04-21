<?php

namespace ConcursosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jurado
 *
 * @ORM\Table(name="jurado")
 * @ORM\Entity(repositoryClass="ConcursosBundle\Repository\JuradoRepository")
 */
class Jurado
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="areaInvestigacion", type="string", length=100)
     */
    private $areaInvestigacion;

    /**
     * @var string
     *
     * @ORM\Column(name="facultad", type="string", length=100, nullable=true)
     */
    private $facultad;

    /**
     * @var string
     *
     * @ORM\Column(name="universidad", type="string", length=100, nullable=true)
     */
    private $universidad;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50)
     */
    private $tipo;
 
    /**
     * @var int
     *
     * @ORM\Column(name="idUsuarioAsigna", type="integer")
     */
    private $idUsuarioAsigna;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=25)
     */
    private $cedula;
    
    protected $concurso;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Jurado
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Jurado
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set areaInvestigacion
     *
     * @param string $areaInvestigacion
     *
     * @return Jurado
     */
    public function setAreaInvestigacion($areaInvestigacion)
    {
        $this->areaInvestigacion = $areaInvestigacion;

        return $this;
    }

    /**
     * Get areaInvestigacion
     *
     * @return string
     */
    public function getAreaInvestigacion()
    {
        return $this->areaInvestigacion;
    }

    /**
     * Set facultad
     *
     * @param string $facultad
     *
     * @return Jurado
     */
    public function setFacultad($facultad)
    {
        $this->facultad = $facultad;

        return $this;
    }

    /**
     * Get facultad
     *
     * @return string
     */
    public function getFacultad()
    {
        return $this->facultad;
    }

    /**
     * Set universidad
     *
     * @param string $universidad
     *
     * @return Jurado
     */
    public function setUniversidad($universidad)
    {
        $this->universidad = $universidad;

        return $this;
    }

    /**
     * Get universidad
     *
     * @return string
     */
    public function getUniversidad()
    {
        return $this->universidad;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Jurado
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set idUsuarioAsigna
     *
     * @param integer $idUsuarioAsigna
     *
     * @return Jurado
     */
    public function setIdUsuarioAsigna($idUsuarioAsigna)
    {
        $this->idUsuarioAsigna = $idUsuarioAsigna;

        return $this;
    }

    /**
     * Get idUsuarioAsigna
     *
     * @return int
     */
    public function getIdUsuarioAsigna()
    {
        return $this->idUsuarioAsigna;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     *
     * @return Jurado
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }
}

