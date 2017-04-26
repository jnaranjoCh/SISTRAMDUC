<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Tramite;

/**
 * @ORM\Entity
 * @ORM\Table(name="tipo_tramite")
 */

class TipoTramite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
//* @ORM\GeneratedValue(strategy="AUTO")
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $duracion;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Tramite", mappedBy="tipo_tramite_id")
     */
    protected $tramites;

    public function __construct()
    {
        $this->tramites = new ArrayCollection();
    }

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
     * Set id
     *
     * @param string $id
     *
     * @return TipoTramite
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TipoTramite
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
     * @return TipoTramite
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
     * @return TipoTramite
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
     * @return TipoTramite
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
