<?php

namespace TramiteBundle\Entity;

/**
 * Tipo_tramite
 */
class Tipo_tramite
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $duracion;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tramites;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tramites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Tipo_tramite
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
     * Set duracion
     *
     * @param string $duracion
     *
     * @return Tipo_tramite
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return string
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Tipo_tramite
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add tramite
     *
     * @param \TramiteBundle\Entity\Tramite $tramite
     *
     * @return Tipo_tramite
     */
    public function addTramite(\TramiteBundle\Entity\Tramite $tramite)
    {
        $this->tramites[] = $tramite;

        return $this;
    }

    /**
     * Remove tramite
     *
     * @param \TramiteBundle\Entity\Tramite $tramite
     */
    public function removeTramite(\TramiteBundle\Entity\Tramite $tramite)
    {
        $this->tramites->removeElement($tramite);
    }

    /**
     * Get tramites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTramites()
    {
        return $this->tramites;
    }
}

