<?php

namespace ConcursoOposicionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recusacion
 *
 * @ORM\Table(name="recusacion")
 * @ORM\Entity(repositoryClass="ConcursoOposicionBundle\Repository\RecusacionRepository")
 */
class Recusacion
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
     * @var int
     *
     * @ORM\Column(name="cedula_aspirante", type="integer")
     */
    private $cedulaAspirante;

    /**
     * @var int
     *
     * @ORM\Column(name="cedula_jurado", type="integer")
     */
    private $cedulaJurado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="usuario", type="integer")
     */
    private $usuario;


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
     * Set cedulaAspirante
     *
     * @param integer $cedulaAspirante
     *
     * @return Recusacion
     */
    public function setCedulaAspirante($cedulaAspirante)
    {
        $this->cedulaAspirante = $cedulaAspirante;

        return $this;
    }

    /**
     * Get cedulaAspirante
     *
     * @return int
     */
    public function getCedulaAspirante()
    {
        return $this->cedulaAspirante;
    }

    /**
     * Set cedulaJurado
     *
     * @param integer $cedulaJurado
     *
     * @return Recusacion
     */
    public function setCedulaJurado($cedulaJurado)
    {
        $this->cedulaJurado = $cedulaJurado;

        return $this;
    }

    /**
     * Get cedulaJurado
     *
     * @return int
     */
    public function getCedulaJurado()
    {
        return $this->cedulaJurado;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Recusacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param integer $usuario
     *
     * @return Recusacion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return int
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

