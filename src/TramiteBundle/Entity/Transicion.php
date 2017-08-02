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
    private $doc_info = "";

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $motivo_consejo = "Motivo";

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transicionesConsejo")
     * @ORM\JoinColumn(name="estadoConsejo_id", referencedColumnName="id")
     */
    protected $estado_consejo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_env_departamento;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $motivo_departamento = "";

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transicionesDepartamento")
     * @ORM\JoinColumn(name="estadoDepartamento_id", referencedColumnName="id")
     */
    protected $estado_departamento;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_env_catedra;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $motivo_catedra = "m";

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="transicionesCatedra")
     * @ORM\JoinColumn(name="estadocatedra_id", referencedColumnName="id")
     */
    protected $estado_catedra;


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

    /**
     * Set fecha_consejo
     *
     * @param datetime $fecha_consejo
     */
    public function setFechaEnvDepartamento($fecha_departamento)
    {
        $this->fecha_env_departamento = $fecha_departamento;
    }

    /**
     * Get fecha_env_departamento
     *
     * @return datetime
     */
    public function getFechaEnvDepartamento()
    {
        return $this->fecha_env_departamento;
    }

    /**
     * Set motivo_departamento
     *
     * @param string $motivo_departamento
     *
     * @return Transicion
     */
    public function setMotivoDepartamento($motivo_departamento)
    {
        $this->motivo_departamento = $motivo_departamento;

        return $this;
    }

    /**
     * Get motivo_departamento
     *
     * @return string
     */
    public function getMotivoDepartamento()
    {
        return $this->motivo_departamento;
    }

    /**
     * Set estado_departamento
     *
     * @param \TramiteBundle\Entity\Estado $estado_departamento
     *
     * @return Estado
     */
    public function setEstadoDepartamento(Estado $estado_departamento = null)
    {
        $this->estado_departamento = $estado_departamento;

        return $this;
    }

    /**
     * Get estado_departamento
     *
     * @return \TramiteBundle\Entity\Estado
     */
    public function getEstadoDepartamento()
    {
        return $this->estado_departamento;
    }

    /**
     * Set fecha_env_catedra
     *
     * @param datetime $fecha_env_catedra
     */
    public function setFechaEnvCatedra($fecha_catedra)
    {
        $this->fecha_env_catedra = $fecha_catedra;
    }

    /**
     * Get fecha_env_catedra
     *
     * @return datetime
     */
    public function getFechaEnvCatedra()
    {
        return $this->fecha_env_catedra;
    }

    /**
     * Set motivo_departamento
     *
     * @param string $motivo_departamento
     *
     * @return Transicion
     */
    public function setMotivocatedra($motivo_departamento)
    {
        $this->motivo_departamento = $motivo_departamento;

        return $this;
    }

    /**
     * Get motivo_catedra
     *
     * @return string
     */
    public function getMotivoCatedra()
    {
        return $this->motivo_catedra;
    }

    /**
     * Set estado_catedra
     *
     * @param \TramiteBundle\Entity\Estado $estado_catedra
     *
     * @return Estado
     */
    public function setEstadoCatedra(Estado $estado_catedra = null)
    {
        $this->estado_catedra = $estado_catedra;

        return $this;
    }

    /**
     * Get estado_catedra
     *
     * @return \TramiteBundle\Entity\Estado
     */
    public function getEstadoCatedra()
    {
        return $this->estado_catedra;
    }
}