<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Estado
 *
 * @ORM\Table(name="estado")
 * @ORM\Entity(repositoryClass="TramiteBundle\Repository\EstadoRepository")
 */
class Estado
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
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Transicion", mappedBy="estado")
     */
    protected $transiciones;

    public function __construct()
    {
        $this->transiciones = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Estado
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Estado
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
     * Add transicion
     *
     * @param \TramiteBundle\Entity\Transicion $transicion
     *
     * @return Transicion
     */
    public function addTransicion(Transicion $transiciones)
    {
        $this->transiciones[] = $transiciones;

        return $this;
    }

    /**
     * Remove transicion
     *
     * @param \TramiteBundle\Entity\Transicion $transicion
     */
    public function removeTransicion(Transicion $transicion)
    {
        $this->transiciones->removeElement($transicion);
    }

    /**
     * Get transiciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransiciones()
    {
        return $this->transiciones;
    }
}

