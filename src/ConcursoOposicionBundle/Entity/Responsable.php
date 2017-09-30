<?php

namespace ConcursoOposicionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ConcursosBundle\Entity\Concurso;

/**
 * Responsable
 *
 * @ORM\Table(name="responsable")
 * @ORM\Entity(repositoryClass="ConcursoOposicionBundle\Repository\ResponsableRepository")
 */
class Responsable
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
     * @ORM\Column(name="cedula_r", type="string", length=25, nullable=true)
     */
    private $cedulaR;

    /**
     * @var string
     *
     * @ORM\Column(name="NyA_r", type="string", length=100, nullable=true)
     */
    private $nyAR;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo_r", type="string", length=100, nullable=true)
     */
    private $cargoR;

    /**
     * @var string
     *
     * @ORM\Column(name="control", type="string", length=20, nullable=true)
     */
    private $control;

    /**
     * @var string
     *
     * @ORM\Column(name="firma_r", type="string", length=50, nullable=true)
     */
    private $firmaR;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_r", type="datetime", nullable=true)
     */
    private $fechaR;

    /**
     * @var string
     *
     * @ORM\Column(name="NyA_e", type="string", length=100, nullable=true)
     */
    private $nyAE;

    /**
     * @var string
     *
     * @ORM\Column(name="firma_e", type="string", length=50, nullable=true)
     */
    private $firmaE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_e", type="datetime", nullable=true)
     */
    private $fechaE;

    /**
     * @var string
     *
     * @ORM\Column(name="presupuesto", type="string", length=5, nullable=true)
     */
    private $presupuesto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio_resolucion", type="datetime", nullable=true)
     */
    private $fechaInicioResolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="avala", type="string", length=5, nullable=true)
     */
    private $avala;

    /**
     * @var string
     *
     * @ORM\Column(name="justificacion", type="string", length=500, nullable=true)
     */
    private $justificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="NyA_resolucion", type="string", length=100, nullable=true)
     */
    private $nyAResolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="firma_resolucion", type="string", length=50, nullable=true)
     */
    private $firmaResolucion;

    /**
     * @ORM\ManyToOne(targetEntity="ConcursosBundle\Entity\Concurso", inversedBy="curso")
     * @ORM\JoinColumn(name="concurso_id", referencedColumnName="id")
     */
    protected $concurso;


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
     * Set cedulaR
     *
     * @param string $cedulaR
     *
     * @return Responsable
     */
    public function setCedulaR($cedulaR)
    {
        $this->cedulaR = $cedulaR;

        return $this;
    }

    /**
     * Get cedulaR
     *
     * @return string
     */
    public function getCedulaR()
    {
        return $this->cedulaR;
    }

    /**
     * Set nyAR
     *
     * @param string $nyAR
     *
     * @return Responsable
     */
    public function setNyAR($nyAR)
    {
        $this->nyAR = $nyAR;

        return $this;
    }

    /**
     * Get nyAR
     *
     * @return string
     */
    public function getNyAR()
    {
        return $this->nyAR;
    }

    /**
     * Set cargoR
     *
     * @param string $cargoR
     *
     * @return Responsable
     */
    public function setCargoR($cargoR)
    {
        $this->cargoR = $cargoR;

        return $this;
    }

    /**
     * Get cargoR
     *
     * @return string
     */
    public function getCargoR()
    {
        return $this->cargoR;
    }

    /**
     * Set control
     *
     * @param string $control
     *
     * @return Responsable
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get control
     *
     * @return string
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set firmaR
     *
     * @param string $firmaR
     *
     * @return Responsable
     */
    public function setFirmaR($firmaR)
    {
        $this->firmaR = $firmaR;

        return $this;
    }

    /**
     * Get firmaR
     *
     * @return string
     */
    public function getFirmaR()
    {
        return $this->firmaR;
    }

    /**
     * Set fechaR
     *
     * @param \DateTime $fechaR
     *
     * @return Responsable
     */
    public function setFechaR($fechaR)
    {
        $this->fechaR = $fechaR;

        return $this;
    }

    /**
     * Get fechaR
     *
     * @return \DateTime
     */
    public function getFechaR()
    {
        return $this->fechaR;
    }

    /**
     * Set nyAE
     *
     * @param string $nyAE
     *
     * @return Responsable
     */
    public function setNyAE($nyAE)
    {
        $this->nyAE = $nyAE;

        return $this;
    }

    /**
     * Get nyAE
     *
     * @return string
     */
    public function getNyAE()
    {
        return $this->nyAE;
    }

    /**
     * Set firmaE
     *
     * @param string $firmaE
     *
     * @return Responsable
     */
    public function setFirmaE($firmaE)
    {
        $this->firmaE = $firmaE;

        return $this;
    }

    /**
     * Get firmaE
     *
     * @return string
     */
    public function getFirmaE()
    {
        return $this->firmaE;
    }

    /**
     * Set fechaE
     *
     * @param \DateTime $fechaE
     *
     * @return Responsable
     */
    public function setFechaE($fechaE)
    {
        $this->fechaE = $fechaE;

        return $this;
    }

    /**
     * Get fechaE
     *
     * @return \DateTime
     */
    public function getFechaE()
    {
        return $this->fechaE;
    }

    /**
     * Set presupuesto
     *
     * @param string $presupuesto
     *
     * @return Responsable
     */
    public function setPresupuesto($presupuesto)
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    /**
     * Get presupuesto
     *
     * @return string
     */
    public function getPresupuesto()
    {
        return $this->presupuesto;
    }

    /**
     * Set fechaInicioResolucion
     *
     * @param \DateTime $fechaInicioResolucion
     *
     * @return Responsable
     */
    public function setFechaInicioResolucion($fechaInicioResolucion)
    {
        $this->fechaInicioResolucion = $fechaInicioResolucion;

        return $this;
    }

    /**
     * Get fechaInicioResolucion
     *
     * @return \DateTime
     */
    public function getFechaInicioResolucion()
    {
        return $this->fechaInicioResolucion;
    }

    /**
     * Set avala
     *
     * @param string $avala
     *
     * @return Responsable
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
     * Set justificacion
     *
     * @param string $justificacion
     *
     * @return Responsable
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

    /**
     * Set nyAResolucion
     *
     * @param string $nyAResolucion
     *
     * @return Responsable
     */
    public function setNyAResolucion($nyAResolucion)
    {
        $this->nyAResolucion = $nyAResolucion;

        return $this;
    }

    /**
     * Get nyAResolucion
     *
     * @return string
     */
    public function getNyAResolucion()
    {
        return $this->nyAResolucion;
    }

    /**
     * Set firmaResolucion
     *
     * @param string $firmaResolucion
     *
     * @return Responsable
     */
    public function setFirmaResolucion($firmaResolucion)
    {
        $this->firmaResolucion = $firmaResolucion;

        return $this;
    }

    /**
     * Get firmaResolucion
     *
     * @return string
     */
    public function getFirmaResolucion()
    {
        return $this->firmaResolucion;
    }

    /**
     * Set concurso
     *
     * @param integer $concurso
     *
     * @return Curso
     */
    public function setConcurso(Concurso $concurso = null)
    {
        $this->concurso = $concurso;

        return $this;
    }

    /**
     * Get concurso
     *
     * @return int
     */
    public function getConcurso()
    {
        return $this->concurso;
    }
}

