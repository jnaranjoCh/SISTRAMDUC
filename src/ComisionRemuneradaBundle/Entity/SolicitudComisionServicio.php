<?php

namespace ComisionRemuneradaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Tramite;
use AppBundle\Entity\Usuario;

/**
 * SolicitudComisionServicio
 * @ORM\Table(name="tramite_comision_servicio")
 * @ORM\Entity(repositoryClass="ComisionRemuneradaBundle\Repository\SolicitudComisionServicioRepository")
 */
class SolicitudComisionServicio extends Tramite
{
    protected $type = "comision";

    protected $recaudos;

    protected $tipo_tramite;
    
    protected $usuario_id;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de la Comisión de Servicio por parte del Beneficiario")
        , new recaudo("Copia de la Designación del cargos en la Administración")
        ));
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

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario)
    {
        $this->usuario_id = $usuario;
    }


    /**
     * Set tipoTramiteId
     *
     * @param \ComisionRemuneradaBundle\Entity\TipoTramite $tipoTramiteId
     *
     * @return SolicitudComisionServicio
     */
    public function setTipoTramiteId(\ComisionRemuneradaBundle\Entity\TipoTramite $tipoTramiteId = null)
    {
        $this->tipo_tramite_id = $tipoTramiteId;

        return $this;
    }

    /**
     * Get tipoTramiteId
     *
     * @return \ComisionRemuneradaBundle\Entity\TipoTramite
     */
    public function getTipoTramiteId()
    {
        return $this->tipo_tramite_id;
    }

    /**
     * Set transicion
     *
     * @param \ComisionRemuneradaBundle\Entity\Transicion $transicion
     *
     * @return SolicitudComisionServicio
     */
    public function setTransicion(\ComisionRemuneradaBundle\Entity\Transicion $transicion = null)
    {
        $this->transicion = $transicion;

        return $this;
    }

    /**
     * Get transicion
     *
     * @return \ComisionRemuneradaBundle\Entity\Transicion
     */
    public function getTransicion()
    {
        return $this->transicion;
    }
}
