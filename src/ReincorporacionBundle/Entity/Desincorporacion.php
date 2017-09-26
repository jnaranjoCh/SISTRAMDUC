<?php

namespace ReincorporacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Desincorporacion
 *
 * @ORM\Table(name="desincorporacion")
 * @ORM\Entity(repositoryClass="ReincorporacionBundle\Repository\DesincorporacionRepository")
 */
class Desincorporacion
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
     * @ORM\Column(name="usuario", type="string", length=255)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha_desincorporacion", type="datetime")
     */
    private $fechaDesincorporacion;

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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Desincorporacion
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

    /**
     * Set fechaDesincorporacion
     *
     * @param string $fechaDesincorporacion
     *
     * @return Desincorporacion
     */
    public function setFechaDesincorporacion($fechaDesincorporacion)
    {
        $this->fechaDesincorporacion = $fechaDesincorporacion;

        return $this;
    }

    /**
     * Get fechaDesincorporacion
     *
     * @return string
     */
    public function getFechaDesincorporacion()
    {
        return $this->fechaDesincorporacion;
    }
}

