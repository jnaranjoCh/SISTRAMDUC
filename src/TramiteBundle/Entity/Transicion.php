<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TramiteBundle\Entity\Tramite;

/**
 * @ORM\Entity
 * @ORM\Table(name="transicion")
 */

class Transicion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $doc_info = "Doc_info";

    /**
     * @ORM\OneToOne(targetEntity="Tramite", mappedBy="transicion")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    protected $tramite;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transiciones")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    protected $estado;


    function __construct(\DateTime $fecha = null){
        $this->fecha = $fecha;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param datetime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set doc_info
     *
     * @param string $doc_info
     *
     * @return Transicion
     */
    public function setDoc_info($doc_info)
    {
        $this->doc_info = $doc_info;

        return $this;
    }

    /**
     * Get doc_info
     *
     * @return string
     */
    public function getDoc_info()
    {
        return $this->doc_info;
    }

    /**
     * Set estado
     *
     * @param \TramiteBundle\Entity\Estado $estado
     *
     * @return Estado
     */
    public function setEstado(Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \TramiteBundle\Entity\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    public function asignarA(Tramite $tramite)
    {
        $this->tramite = $tramite;
        $tramite->ownTransicion($this);

        return $this;
    }

    public function __toString()
    {
        return sprintf($this->getEstado());
    }
}