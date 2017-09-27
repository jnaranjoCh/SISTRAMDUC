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
     * @ORM\Column(name="usuario", type="integer")
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha_desincorporacion", type="datetime")
     */
    private $fecha_desincorporacion;

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
     * @param integer $usuario
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
     * @return integer
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha_desincorporacion
     *
     * @param string $fecha_desincorporacion
     *
     * @return Desincorporacion
     */
    public function setFechaDesincorporacion($fecha_desincorporacion)
    {
        $this->fecha_desincorporacion = $fecha_desincorporacion;

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

