<?php

namespace ReincorporacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TramiteBundle\Entity\Tramite as Tramite;

/**
 * TramiteReincorporacion
 *
 * @ORM\Table(name="tramite_reincorporacion")
 * @ORM\Entity(repositoryClass="ReincorporacionBundle\Repository\TramiteReincorporacionRepository")
 */
class TramiteReincorporacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    private $type;

    private $recaudos;

    /**
     * @var integer
     *
     * @ORM\Column(name="tramite", type="integer")
     */
    private $tramite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reincorporacion", type="date")
     */
    private $fecha_reincorporacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="usuario", type="integer")
     */
    private $usuario;

    public function __construct()
    {
        $this->type = "reincorporacion";
        $this->recaudos = new ArrayCollection(
            array(
                new recaudo("Designación como docente"),
                new recaudo("Oficio de la Ubicación"),
                new recaudo("Oficio del úitimo ascenso"),
                new recaudo("Oficio de la aceptación de la renuncia"),
                new recaudo("Fondo negro del título (solo IV o V nivel)"),
                new recaudo("Declaración jurada de cargos ejercidos durante el período fuera de la Universidad de Carabobo")
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
     * Set tramite
     *
     * @param integer $tramite
     *
     * @return TramiteReincorporacion
     */
    public function setTipoTramite($tramite)
    {
        $this->tramite = $tramite;

        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return integer
     */
    public function getTipoTramite()
    {
        return $this->tramite;
    }

    /**
     * Set fecha_reincorporacion
     *
     * @param \DateTime $fecha_reincorporacion
     *
     * @return TramiteReincorporacion
     */
    public function setFechaReincorporacion($fecha_reincorporacion)
    {
        $this->fecha_reincorporacion = $fecha_reincorporacion;

        return $this;
    }

    /**
     * Get fecha_reincorporacion
     *
     * @return \DateTime
     */
    public function getFechaReincorporacion()
    {
        return $this->fecha_reincorporacion;
    }

    /**
     * Set usuario
     *
     * @param integer $usuario
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
     * @return integer
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

