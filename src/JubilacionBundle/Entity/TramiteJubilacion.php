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

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de la Comisión de Servicio por parte del Beneficiario")
        ,new recaudo("Fotocopia de cédula de Identidad"),new recaudo("Fotocopia del RIF"),
            new recaudo("Copia de la Designación del cargos en la Administración"), new recaudo("Fotocopia del RIF")
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
}


