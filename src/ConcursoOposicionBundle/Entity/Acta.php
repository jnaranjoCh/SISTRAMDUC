<?php

namespace ConcursoOposicionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use ConcursoOposicionBundle\Entity\Autorizadores;

/**
 * Acta
 *
 * @ORM\Table(name="acta")
 * @ORM\Entity(repositoryClass="ConcursoOposicionBundle\Repository\ActaRepository")
 */
class Acta
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="nro_acta", type="string", length=50)
     */
    private $nroActa;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar", type="string", length=500, nullable=true)
     */
    private $lugar;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", length=500, nullable=true)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="resolucion", type="string", length=200, nullable=true)
     */
    private $resolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="avala", type="string", length=5, nullable=true)
     */
    private $avala;

    /**
     * @var string
     *
     * @ORM\Column(name="justificacion", type="string", length=200, nullable=true)
     */
    private $justificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="ampm", type="string", length=4, nullable=true)
     */
    private $ampm;

    /**
     * @ORM\OneToMany(targetEntity="ConcursoOposicionBundle\Entity\Autorizadores", mappedBy="acta", cascade={"remove", "persist"})
     */
    protected $autorizadores;

    public function __construct()
    {
       $this->autorizadores = new ArrayCollection();
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Acta
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
     * Set nroActa
     *
     * @param string $nroActa
     *
     * @return Acta
     */
    public function setNroActa($nroActa)
    {
        $this->nroActa = $nroActa;

        return $this;
    }

    /**
     * Get nroActa
     *
     * @return string
     */
    public function getNroActa()
    {
        return $this->nroActa;
    }

    /**
     * Set lugar
     *
     * @param string $lugar
     *
     * @return Acta
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;

        return $this;
    }

    /**
     * Get lugar
     *
     * @return string
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return Acta
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set resolucion
     *
     * @param string $resolucion
     *
     * @return Acta
     */
    public function setResolucion($resolucion)
    {
        $this->resolucion = $resolucion;

        return $this;
    }

    /**
     * Get resolucion
     *
     * @return string
     */
    public function getResolucion()
    {
        return $this->resolucion;
    }

    /**
     * Set avala
     *
     * @param string $avala
     *
     * @return Acta
     */
    public function setAvala($avala)
    {
        $this->avala = $avala;

        return $this;
    }

    /**
     * Get avala
     *
     * @return string
     */
    public function getAvala()
    {
        return $this->avala;
    }

    /**
     * Set ampm
     *
     * @param string $ampm
     *
     * @return ampm
     */
    public function setAmpm($ampm)
    {
        $this->ampm = $ampm;

        return $this;
    }

    /**
     * Get ampm
     *
     * @return string
     */
    public function getAmpm()
    {
        return $this->ampm;
    }

    /**
     * Set justificacion
     *
     * @param string $justificacion
     *
     * @return Acta
     */
    public function setJustificacion($justificacion)
    {
        $this->justificacion = $justificacion;

        return $this;
    }

    /**
     * Get justificacion
     *
     * @return string
     */
    public function getJustificacion()
    {
        return $this->justificacion;
    }

    public function addAutorizadores(Autorizadores $autorizadores)
    {
        if (!$this->autorizadores->contains($autorizadores)) {

            $this->autorizadores->add($autorizadores);
            $autorizadores->setActa($this);
        }

        return $this;
    }

    public function getAutorizadores()
    {
        return $this->autorizadores;
    }
}

