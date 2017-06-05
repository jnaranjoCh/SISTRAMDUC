<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TramiteBundle\Entity\Tramite;

/**
 * @ORM\Entity(repositoryClass="TransicionRepository")
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
     * @ORM\Column(type="text")
     */
    private $doc_info = "Motivo";

    /**
     * @ORM\OneToOne(targetEntity="Tramite", inversedBy="transicion")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    protected $tramite;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transiciones")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    protected $estado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_consejo;

    /**
     * @ORM\Column(type="text")
     */
    private $motivo_consejo = "Motivo";

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transicionesConsejo")
     * @ORM\JoinColumn(name="estadoConsejo_id", referencedColumnName="id")
     */
    protected $estado_consejo;


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
     * Set fecha_consejo
     *
     * @param datetime $fecha_consejo
     */
    public function setFechaConsejo($fecha_consejo)
    {
        $this->fecha_consejo = $fecha_consejo;
    }

    /**
     * Get fecha_consejo
     *
     * @return datetime
     */
    public function getFechaConsejo()
    {
        return $this->fecha_consejo;
    }

    /**
     * Set motivo_consejo
     *
     * @param string $motivo_consejo
     *
     * @return Transicion
     */
    public function setMotivoConsejo($motivo_consejo)
    {
        $this->motivo_consejo = $motivo_consejo;

        return $this;
    }

    /**
     * Get motivo_consejo
     *
     * @return string
     */
    public function getMotivoConsejo()
    {
        return $this->motivo_consejo;
    }
    
    /**
     * Set estado_consejo
     *
     * @param \TramiteBundle\Entity\Estado $estado_consejo
     *
     * @return Estado
     */
    public function setEstadoConsejo(Estado $estado_consejo = null)
    {
        $this->estado_consejo = $estado_consejo;

        return $this;
    }

    /**
     * Get estado_consejo
     *
     * @return \TramiteBundle\Entity\Estado
     */
    public function getEstadoConsejo()
    {
        return $this->estado_consejo;
    }
    
    public function fechaNula()
    {
        if ($this->fecha_consejo == null)
        {
            return(true);
        }else{
           return(false); 
        }
    }

}