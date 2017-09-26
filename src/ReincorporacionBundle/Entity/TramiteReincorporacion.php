<?php

namespace ReincorporacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TramiteBundle\Entity\Tramite;

/**
 * TramiteReincorporacion
 *
 * @ORM\Table(name="tramite_reincorporacion")
 * @ORM\Entity(repositoryClass="ReincorporacionBundle\Repository\TramiteReincorporacionRepository")
 */
class TramiteReincorporacion extends Tramite
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    protected $recaudos;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_tramite", type="string", length=255)
     */
    private $tipoTramite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reincorporacion", type="date")
     */
    private $fechaReincorporacion;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=255)
     */
    private $usuario;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de Jubilación")
        ,new recaudo("Constancia para efecto de Jubilación"),new recaudo("Constancia de Antecedentes de servicios del ente de la administración pública donde laboró"),
            new recaudo("Constancia de cumplimiento de labores como preparador"), new recaudo("Recibo de Pago")
        ));
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
     * Set type
     *
     * @param string $type
     *
     * @return TramiteReincorporacion
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get recaudos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecaudos()
    {
        return $this->recaudos;
    }

    public function __toString() {
        return sprintf($this->getId());
    }

    /**
     * Set tipoTramite
     *
     * @param string $tipoTramite
     *
     * @return TramiteReincorporacion
     */
    public function setTipoTramite($tipoTramite)
    {
        $this->tipoTramite = $tipoTramite;

        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return string
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

    /**
     * Set fechaReincorporacion
     *
     * @param \DateTime $fechaReincorporacion
     *
     * @return TramiteReincorporacion
     */
    public function setFechaReincorporacion($fechaReincorporacion)
    {
        $this->fechaReincorporacion = $fechaReincorporacion;

        return $this;
    }

    /**
     * Get fechaReincorporacion
     *
     * @return \DateTime
     */
    public function getFechaReincorporacion()
    {
        return $this->fechaReincorporacion;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TramiteReincorporacion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

