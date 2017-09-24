<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TramiteBundle\Entity\Tramite;
use TramiteBundle\Entity\Estado;

/**
 * @ORM\Entity
 * @ORM\Table(name="transicion_consejo")
 */

class TransicionConsejo
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
     * @ORM\Column(type="text")
     */
    private $doc_info = "Motivo";

    /**
     * @ORM\OneToOne(targetEntity="Tramite", inversedBy="transicionConsejo")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    protected $tramite;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transicionesConsejo")
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
    public function setEstado(Estado $estado)
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

    /**
     * Get tramite
     *
     * @return \TramiteBundle\Entity\Tramite
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set docInfo
     *
     * @param string $docInfo
     *
     * @return TransicionConsejo
     */
    public function setDocInfo($docInfo)
    {
        $this->doc_info = $docInfo;

        return $this;
    }

    /**
     * Get docInfo
     *
     * @return string
     */
    public function getDocInfo()
    {
        return $this->doc_info;
    }

    /**
     * Set tramite
     *
     * @param \TramiteBundle\Entity\Tramite $tramite
     *
     * @return TransicionConsejo
     */
    public function setTramite(\TramiteBundle\Entity\Tramite $tramite = null)
    {
        $this->tramite = $tramite;

        return $this;
    }
}
