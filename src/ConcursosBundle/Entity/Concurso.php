<?php

namespace ConcursosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Tramite;
use AppBundle\Entity\Usuario;
use TramiteBundle\Entity\Transicion;
use ConcursoOposicionBundle\Entity\Curso;

/**
 * Concurso
 * @ORM\Table(name="concurso")
 * @ORM\Entity(repositoryClass="ConcursosBundle\Repository\ConcursoRepository")
 */
class Concurso extends Tramite
{
    protected $type = "concurso";

    protected $recaudos;
    
    protected $transiciones;
    
    protected $tipo_tramite_id;
    
    protected $usuario_id;
    
    // /**
    //  * @var int
    //  *
    //  * @ORM\Column(name="id", type="integer")
    //  * @ORM\Id
    //  * @ORM\GeneratedValue(strategy="AUTO")
    //  */
    // private $id;

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
     * @var string
     *
     * @ORM\Column(name="temaExEscrito", type="string", nullable=true)
     */
    private $temaExEscrito;
    
      /**
     * @var string
     *
     * @ORM\Column(name="temaExOral", type="string", nullable=true)
     */
    private $temaExOral;
    
    /**
     * @ORM\ManyToMany(targetEntity="Aspirante", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="concurso_aspirante",
     *      joinColumns={@ORM\JoinColumn(name="concurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="aspirante_id", referencedColumnName="id")}
     *      )
     */
    protected $aspirantes;
    
    /**
     * @ORM\ManyToMany(targetEntity="Jurado", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="concurso_jurado",
     *      joinColumns={@ORM\JoinColumn(name="concurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="jurado_id", referencedColumnName="id")}
     *      )
     */
    protected $jurado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="condicion", type="string", length=50, nullable=true)
     */
    private $condicion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tiempo_dedicacion", type="string", length=50, nullable=true)
     */
    private $tiempo_dedicacion;
    
    /**
     * @var int
     *
     * @ORM\Column(name="nro_horas", type="integer", nullable=true)
     */
    private $nro_horas;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facultad", type="string", length=100, nullable=true)
     */
    private $facultad;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sede", type="string", length=50, nullable=true)
     */
    private $sede;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=50, nullable=true)
     */
    private $ciudad;
    
    /**
     * @var string
     *
     * @ORM\Column(name="escuela", type="string", length=50, nullable=true)
     */
    private $escuela;
    
    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=200, nullable=true)
     */
    private $departamento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=150, nullable=true)
     */
    private $motivo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="desc_motivo", type="string", length=150, nullable=true)
     */
    private $desc_motivo;
        
    /**
     * @var string
     *
     * @ORM\Column(name="justificacion", type="string", length=500, nullable=true)
     */
    private $justificacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="grado_academico", type="string", length=100, nullable=true)
     */
    private $grado_academico;
    
    /**
     * @var string
     *
     * @ORM\Column(name="profesion", type="string", length=150, nullable=true)
     */
    private $profesion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="experiencia", type="string", length=5, nullable=true)
     */
    private $experiencia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area_conocimiento", type="string", length=150, nullable=true)
     */
    private $area_conocimiento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area_investigacion", type="string", length=150, nullable=true)
     */
    private $area_investigacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area_extension", type="string", length=150, nullable=true)
     */
    private $area_extension;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="ConcursoOposicionBundle\Entity\Curso", mappedBy="concurso", cascade={"remove", "persist"})
     */
    protected $curso;
    
    /**
     * @var int
     *
     * @ORM\Column(name="idUsuarioAct", type="integer", nullable=true)
     */
    private $idUsuarioAct;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    public function __construct()
    {
       $this->jurado = new ArrayCollection();
       $this->aspirantes = new ArrayCollection();
       $this->curso = new ArrayCollection();
    }
    
    // /**
    //  * Get id
    //  *
    //  * @return int
    //  */
    // public function getId()
    // {
    //     return $this->id;
    // }

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
        $existentes = $this->getJurados();
        $existe=false;
        $i=0;
        foreach($existentes as $value){
            if($value == $jurados){
               $existe=true;
               break;
            }
            $i=$i+1;
        }
        if(!$existe){$this->jurado[] = $jurados;}
    }

    public function addJurados($jurado)
    {
       foreach($jurado as $jurados)
           $this->addJurado($jurados);
    }
    
    public function getJurados()
    {
    	return $this->jurado->toArray();
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
    
    public function getAspirantes()
    {
    	return $this->aspirantes->toArray();
    }
    
    /**
     * Set temaExEscrito
     *
     * @param string $temaExEscrito
     *
     * @return Concurso
     */
    public function setTemaExEscrito($temaExEscrito)
    {
        $this->temaExEscrito = $temaExEscrito;

        return $this;
    }

    /**
     * Get temaExEscrito
     *
     * @return string
     */
    public function getTemaExEscrito()
    {
        return $this->temaExEscrito;
    }
    
    /**
     * Set temaExOral
     *
     * @param string $temaExOral
     *
     * @return Concurso
     */
    public function setTemaExOral($temaExOral)
    {
        $this->temaExOral = $temaExOral;

        return $this;
    }

    /**
     * Get temaExOral
     *
     * @return string
     */
    public function getTemaExOral()
    {
        return $this->temaExOral;
    }
    
    /**
     * Set condicion
     *
     * @param string $condicion
     *
     * @return Concurso
     */
    public function setCondicion($condicion)
    {
    	$this->condicion = $condicion;
    
    	return $this;
    }
    
    /**
     * Get condicion
     *
     * @return string
     */
    public function getCondicion()
    {
    	return $this->condicion;
    }
    
    /**
     * Set tiempo_dedicacion
     *
     * @param string $tiempo_dedicacion
     *
     * @return Concurso
     */
    public function setTiempoDedicacion($tiempo_dedicacion)
    {
    	$this->tiempo_dedicacion = $tiempo_dedicacion;
    
    	return $this;
    }
    
    /**
     * Get tiempo_dedicacion
     *
     * @return string
     */
    public function getTiempoDedicacion()
    {
    	return $this->tiempo_dedicacion;
    }
    
    /**
     * Set nro_horas
     *
     * @param integer $nro_horas
     *
     * @return Concurso
     */
    public function setNroHoras($nro_horas)
    {
    	$this->nro_horas = $nro_horas;
    
    	return $this;
    }
    
    /**
     * Get nro_horas
     *
     * @return int
     */
    public function getNroHoras()
    {
    	return $this->nro_horas;
    }
    
    /**
     * Set facultad
     *
     * @param string $facultad
     *
     * @return Concurso
     */
    public function setFacultad($facultad)
    {
    	$this->facultad = $facultad;
    
    	return $this;
    }
    
    /**
     * Get facultad
     *
     * @return string
     */
    public function getFacultad()
    {
    	return $this->facultad;
    }
    
    /**
     * Set sede
     *
     * @param string $sede
     *
     * @return Concurso
     */
    public function setSede($sede)
    {
    	$this->sede = $sede;
    
    	return $this;
    }
    
    /**
     * Get sede
     *
     * @return string
     */
    public function getSede()
    {
    	return $this->sede;
    }
    
    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Concurso
     */
    public function setCiudad($ciudad)
    {
    	$this->ciudad = $ciudad;
    
    	return $this;
    }
    
    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
    	return $this->ciudad;
    }
    
    /**
     * Set escuela
     *
     * @param string $escuela
     *
     * @return Concurso
     */
    public function setEscuela($escuela)
    {
    	$this->escuela = $escuela;
    
    	return $this;
    }
    
    /**
     * Get escuela
     *
     * @return string
     */
    public function getEscuela()
    {
    	return $this->escuela;
    }
    
    /**
     * Set departamento
     *
     * @param string $departamento
     *
     * @return Concurso
     */
    public function setDepartamento($departamento)
    {
    	$this->departamento = $departamento;
    
    	return $this;
    }
    
    /**
     * Get departamento
     *
     * @return string
     */
    public function getDepartamento()
    {
    	return $this->departamento;
    }
    
    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return Concurso
     */
    public function setMotivo($motivo)
    {
    	$this->motivo = $motivo;
    
    	return $this;
    }
    
    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
    	return $this->motivo;
    }
    
    /**
     * Set desc_motivo
     *
     * @param string $desc_motivo
     *
     * @return Concurso
     */
    public function setDescMotivo($desc_motivo)
    {
    	$this->desc_motivo = $desc_motivo;
    
    	return $this;
    }
    
    /**
     * Get desc_motivo
     *
     * @return string
     */
    public function getDescMotivo()
    {
    	return $this->desc_motivo;
    }
    
    /////////////////
    
    /**
     * Set justificacion
     *
     * @param string $justificacion
     *
     * @return Concurso
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
     * Set grado_academico
     *
     * @param string $grado_academico
     *
     * @return Concurso
     */
    public function setGradoAcademico($grado_academico)
    {
    	$this->grado_academico = $grado_academico;
    
    	return $this;
    }
    
    /**
     * Get grado_academico
     *
     * @return string
     */
    public function getGradoAcademico()
    {
    	return $this->grado_academico;
    }
    
    /**
     * Set profesion
     *
     * @param string $profesion
     *
     * @return Concurso
     */
    public function setProfesion($profesion)
    {
    	$this->profesion = $profesion;
    
    	return $this;
    }
    
    /**
     * Get profesion
     *
     * @return string
     */
    public function getProfesion()
    {
    	return $this->profesion;
    }
    
    /**
     * Set experiencia
     *
     * @param string $experiencia
     *
     * @return Concurso
     */
    public function setExperiencia($experiencia)
    {
    	$this->experiencia = $experiencia;
    
    	return $this;
    }
    
    /**
     * Get experiencia
     *
     * @return string
     */
    public function getExperiencia()
    {
    	return $this->experiencia;
    }
    
    /**
     * Set area_conocimiento
     *
     * @param string $area_conocimiento
     *
     * @return Concurso
     */
    public function setAreaConocimiento($area_conocimiento)
    {
    	$this->area_conocimiento = $area_conocimiento;
    
    	return $this;
    }
    
    /**
     * Get area_conocimiento
     *
     * @return string
     */
    public function getAreaConocimiento()
    {
    	return $this->area_conocimiento;
    }
    
    /**
     * Set area_investigacion
     *
     * @param string $area_investigacion
     *
     * @return Concurso
     */
    public function setAreaInvestigacion($area_investigacion)
    {
    	$this->area_investigacion = $area_investigacion;
    
    	return $this;
    }
    
    /**
     * Get area_investigacion
     *
     * @return string
     */
    public function getAreaInvestigacion()
    {
    	return $this->area_investigacion;
    }
    
    /**
     * Set area_extension
     *
     * @param string $area_extension
     *
     * @return Concurso
     */
    public function setAreaExtension($area_extension)
    {
    	$this->area_extension = $area_extension;
    
    	return $this;
    }
    
    /**
     * Get area_extension
     *
     * @return string
     */
    public function getAreaExtension()
    {
    	return $this->area_extension;
    }
    
    /**
     * Set status
     *
     * @param string $status
     *
     * @return Concurso
     */
    public function setStatus($status)
    {
    	$this->status = $status;
    
    	return $this;
    }
    
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
    	return $this->status;
    }
    
    /**
     * Set idUsuarioAct
     *
     * @param integer $idUsuarioAct
     *
     * @return Concurso
     */
    public function setIdUsuarioAct($idUsuarioAct)
    {
    	$this->idUsuarioAct = $idUsuarioAct;
    
    	return $this;
    }
    
    /**
     * Get idUsuarioAct
     *
     * @return int
     */
    public function getIdUsuarioAct()
    {
    	return $this->idUsuarioAct;
    }  
    
    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Concurso
     */
    public function setNombre($nombre)
    {
    	$this->nombre = $nombre;
    
    	return $this;
    }
    
    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
    	return $this->nombre;
    }
}