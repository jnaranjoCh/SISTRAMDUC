<?php

namespace ConcursosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Concurso
 *
 * @ORM\Table(name="concurso")
 * @ORM\Entity(repositoryClass="ConcursosBundle\Repository\ConcursoRepository")
 */
class Concurso
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
     * @ORM\Column(name="fechaInicio", type="datetime", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var int
     *
     * @ORM\Column(name="nroVacantes", type="integer")
     */
    private $nroVacantes;

    /**
     * @var string
     *
     * @ORM\Column(name="areaPostulacion", type="string", length=100)
     */
    private $areaPostulacion;

    /**
     * @var int
     *
     * @ORM\Column(name="idUsuario", type="integer")
     */
    private $idUsuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaRecepDoc", type="datetime", nullable=true)
     */
    private $fechaRecepDoc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaPresentacion", type="datetime", nullable=true)
     */
    private $fechaPresentacion;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=100)
     */
    private $tipo;
    
    /**
     * @ORM\ManyToMany(targetEntity="ConcursosBundle\Entity\Aspirante")
     * @ORM\JoinTable(name="concurso_aspirante",
     *      joinColumns={@ORM\JoinColumn(name="concurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="aspirante_id", referencedColumnName="id")}
     *      )
     */
    protected $aspirantes;
    
    /**
     * @ORM\ManyToMany(targetEntity="ConcursosBundle\Entity\Jurado")
     * @ORM\JoinTable(name="concurso_jurado",
     *      joinColumns={@ORM\JoinColumn(name="concurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="jurado_id", referencedColumnName="id")}
     *      )
     */
    protected $jurado;

    public function __construct()
    {
       $this->jurado = new ArrayCollection();
       $this->aspirantes = new ArrayCollection();
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Concurso
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set nroVacantes
     *
     * @param integer $nroVacantes
     *
     * @return Concurso
     */
    public function setNroVacantes($nroVacantes)
    {
        $this->nroVacantes = $nroVacantes;

        return $this;
    }

    /**
     * Get nroVacantes
     *
     * @return int
     */
    public function getNroVacantes()
    {
        return $this->nroVacantes;
    }

    /**
     * Set areaPostulacion
     *
     * @param string $areaPostulacion
     *
     * @return Concurso
     */
    public function setAreaPostulacion($areaPostulacion)
    {
        $this->areaPostulacion = $areaPostulacion;

        return $this;
    }

    /**
     * Get areaPostulacion
     *
     * @return string
     */
    public function getAreaPostulacion()
    {
        return $this->areaPostulacion;
    }

    /**
     * Set idUsuario
     *
     * @param integer $idUsuario
     *
     * @return Concurso
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set fechaRecepDoc
     *
     * @param \DateTime $fechaRecepDoc
     *
     * @return Concurso
     */
    public function setFechaRecepDoc($fechaRecepDoc)
    {
        $this->fechaRecepDoc = $fechaRecepDoc;

        return $this;
    }

    /**
     * Get fechaRecepDoc
     *
     * @return \DateTime
     */
    public function getFechaRecepDoc()
    {
        return $this->fechaRecepDoc;
    }

    /**
     * Set fechaPresentacion
     *
     * @param \DateTime $fechaPresentacion
     *
     * @return Concurso
     */
    public function setFechaPresentacion($fechaPresentacion)
    {
        $this->fechaPresentacion = $fechaPresentacion;

        return $this;
    }

    /**
     * Get fechaPresentacion
     *
     * @return \DateTime
     */
    public function getFechaPresentacion()
    {
        return $this->fechaPresentacion;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Concurso
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Concurso
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    public function addJurado($jurados)
    {
       $this->jurado[] = $jurados;
    }

    public function addJurados($jurado)
    {
       foreach($jurado as $jurados)
           $this->addJurado($jurados);
    }

    public function addAspirante($aspirante)
    {
       $this->aspirantes[] = $aspirante;
    }

    public function addAspirantes($aspirante)
    {
       foreach($aspirante as $aspirantes)
           $this->addAspirante($aspirantes);
    }
}

