<?php

namespace JubilacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Tramite;

/**
 * TramiteJubilacion
 *
 * @ORM\Table(name="tramite_jubilacion")
 * @ORM\Entity(repositoryClass="JubilacionBundle\Repository\TramiteJubilacionRepository")
 */
class TramiteJubilacion extends Tramite
{
    protected $type = "jubilacion";

    protected $recaudos;

    protected $tipo_tramite;

    protected $owner;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_recibido;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de Jubilación")
        ,new recaudo("Constancia para efecto de Jubilación"),new recaudo("Constancia de Antecedentes de servicios del ente de la administración pública donde laboró"),
            new recaudo("Constancia de cumplimiento de labores como preparador"), new recaudo("Recibo de Pago")
        ));
    }

    /**
     * Get fecha_recibido
     * @return datetime
     */
    public function getfecha_recibido()
    {
        return $this->fecha_recibido;
    }

    /**
     * Set fecha_recibido
     * @param datetime fecha_recibido
     */
    public function setfecha_recibido($fecha_recibido)
    {
        $this->fecha_recibido = $fecha_recibido;
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
}


