<?php

namespace TramiteBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Duracion
 *
 * @ORM\Table(name="duracion")
 * @ORM\Entity(repositoryClass="TramiteBundle\Repository\DuracionRepository")
 */
class Duracion
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
     * @ORM\Column(name="descripcion", type="string", length=50)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=50)
     */
    private $valor;

     /**
     * @ORM\OneToMany(targetEntity="TramiteBundle\Entity\Recaudo", mappedBy="duracion", cascade={"remove", "persist"})
     */
     protected $recaudos;

    function __construct(){
        $this->recaudos = new ArrayCollection();

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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Duracion
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
     * Set valor
     *
     * @param integer $valor
     *
     * @return Duracion
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return int
     */
    public function getValor()
    {
        return $this->valor;
    }

    public function getRecaudos()
    {
        return $this->recaudos->toArray();
    }
    
    public function setRecaudos($recaudos)
    {
        $this->recaudos = $recaudos;
        return $this;
    }
}

