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
 *
 * @ORM\Table(name="solicitud_comision_servicio")
 * @ORM\Entity(repositoryClass="ComisionRemuneradaBundle\Repository\SolicitudComisionServicioRepository")
 */
class SolicitudComisionServicio extends Tramite
{
    protected $type = "comision";

    protected $recaudos;

    protected $tipo_tramite;
    
    protected $owner;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de la Comisión de Servicio por parte del Beneficiario")
        ,new recaudo("Fotocopia de cédula de Identidad"),new recaudo("Fotocopia del RIF"),
            new recaudo("Copia de la Designación del cargos en la Administración")
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

    /**
     * Add recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     * @return SolicitudComisionServicio
     */
    public function addRecaudo( \TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos[] = $recaudo;
        $recaudo->setSolicitudComisionServicio($this);

        return $this;
    }
    
    public function removeCapitulo(\TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos->removeElement($recaudo);
        $recaudo->setSolicitudComisionServicio(null);
    }
    
    public function removeAllRecaudos()
    {
        $this->recaudos->clear();
    }

    /*public function assignTo(Usuario $owner)
    {
        $this->owner = $owner;
        $owner->ownSolicitudComisionServicio($this);

        return $this;
    }*/

    public function __toString() {
        return sprintf($this->getId());
    }

    /**
     * Add tramite
     *
     * @return SolicitudComisionServicio
     */
    /*public function addTramite(Tramite $tramite)
    {
        $this->tramite = $tramite;
        $tramite->setSolicitudComisionServicio($this);
        return $this;
    }*/

    /**
     * Get Tramite
     */
    /*public function getTramite()
    {
        return $this->tramite;
    }*/

    
}

