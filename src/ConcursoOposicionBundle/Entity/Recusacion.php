<?php

namespace ConcursoOposicionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ConcursosBundle\Entity\Jurado;
use ConcursosBundle\Entity\Aspirante;

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
     * @ORM\ManyToOne(targetEntity="ConcursosBundle\Entity\Jurado", inversedBy="recusa")
     * @ORM\JoinColumn(name="jurado_id", referencedColumnName="id")
     */
    protected $jurado_id;

    /**
     * @ORM\ManyToOne(targetEntity="ConcursosBundle\Entity\Aspirante", inversedBy="recusa")
     * @ORM\JoinColumn(name="aspirante_id", referencedColumnName="id")
     */
    protected $aspirante_id;

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

    public function getJuradoId()
    {
        return $this->jurado_id;
    }

    public function setJuradoId(Jurado $jurado = null)
    {
        $this->jurado_id = $jurado;
        return $this;
    }

    public function getAspiranteId()
    {
        return $this->aspirante_id;
    }

    public function setAspiranteId(Aspirante $aspirante = null)
    {
        $this->aspirante_id = $aspirante;
        return $this;
    }
}

