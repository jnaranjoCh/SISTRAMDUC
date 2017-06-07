<?php

namespace ReincorporacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Tramite;

/**
 * TramiteReincorporacion
 *
 * @ORM\Table(name="tramite_reincorporacion")
 * @ORM\Entity(repositoryClass="ReincorporacionBundle\Repository\TramiteReincorporacionRepository")
 * @ORM\DiscriminatorMap({
 *     "tramite" = "Tramite"
 * })
 */
class TramiteReincorporacion extends Tramite
{
    protected $type = "reincorporacion";

    protected $recaudos;

    protected $tipo_tramite;

    protected $owner;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(
            array(
                new recaudo("Designación como docente"),
                new recaudo("Oficio de la Ubicación"),
                new recaudo("Oficio del úitimo ascenso"),
                new recaudo("Oficio de la aceptación de la renuncia"), 
                new recaudo("Fondo negro del título"),
                new recaudo("Declaración jurada de cargos ejercidos durante el período fuera de la Universidad de Carabobo")
            )
        );
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

