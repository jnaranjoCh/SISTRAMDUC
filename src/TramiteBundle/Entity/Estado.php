<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ConcursosBundle\Entity\Aspirante;

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

    /**
     * @ORM\OneToMany(targetEntity="Transicion", mappedBy="estado_consejo")
     */
    protected $transicionesConsejo;

    /**
     * @ORM\OneToMany(targetEntity="Transicion", mappedBy="estado_departamento")
     */
    protected $transicionesDepartamento;

    /**
     * @ORM\OneToMany(targetEntity="Transicion", mappedBy="estado_catedra")
     */
    protected $transicionesCatedra;
    
    /**
     * @ORM\OneToMany(targetEntity="ConcursosBundle\Entity\Aspirante", mappedBy="estado")
     */
    protected $aspirantes;
    
    public function __construct()
    {
        $this->transiciones = new ArrayCollection();
        $this->aspirantes = new ArrayCollection();
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

    public function __toString()
    {
        return sprintf($this->getNombre());
    }

    /**
     * Add transicionConsejo
     *
     * @param \TramiteBundle\Entity\TransicionConsejo $transicionConsejo
     *
     * @return TransicionConsejo
     */
    public function addTransicionConsejo(TransicionConsejo $transicionesConsejo)
    {
        $this->transicionesConsejo[] = $transicionesConsejo;

        return $this;
    }

    /**
     * Remove transicionConsejo
     *
     * @param \TramiteBundle\Entity\TransicionConsejo $transicionConsejo
     */
    public function removeTransicionConsejo(Transicion $transicionConsejo)
    {
        $this->transicionesConsejo->removeElement($transicionConsejo);
    }

    /**
     * Get transicionesConsejo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransicionesConsejo()
    {
        return $this->transicionesConsejo;
    }
    
    /**
     * Add aspirante
     * @param \ConcursosBundle\Entity\Aspirante $aspirante
     * @return aspirante
     */
    public function addAspirante(\ConcursosBundle\Entity\Aspirante $aspirante)
    {
        $this->aspirantes[] = $aspirante;
        return $this;
    }

    /**
     * Remove aspirante
     * @param \ConcursosBundle\Entity\Aspirante $aspirante
     */
    public function removeAspirante(\ConcursosBundle\Entity\Aspirante $aspirante)
    {
        $this->aspirantes->removeElement($aspirante);
    }

    /**
     * Get aspirante
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAspirante()
    {
        return $this->aspirantes;
    }
}

