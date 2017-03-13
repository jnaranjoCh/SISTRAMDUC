<?php

namespace ConcursosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aspirante
 *
 * @ORM\Table(name="aspirante")
 * @ORM\Entity(repositoryClass="ConcursosBundle\Repository\AspiranteRepository")
 */
class Aspirante
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
     * @ORM\Column(name="primerNombre", type="string", length=50)
     */
    private $primerNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoNombre", type="string", length=50)
     */
    private $segundoNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="primerApellido", type="string", length=50)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoApellido", type="string", length=50)
     */
    private $segundoApellido;

    /**
     * @var int
     *
     * @ORM\Column(name="telefono", type="integer")
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=50)
     */
    private $correo;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=25)
     */
    private $cedula;

    /**
     * @var int
     *
     * @ORM\Column(name="telefonoSecundario", type="integer", nullable=true)
     */
    private $telefonoSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="universidadEgresado", type="string", length=100, nullable=true)
     */
    private $universidadEgresado;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcionTituloUniv", type="string", length=100, nullable=true)
     */
    private $descripcionTituloUniv;

    /**
     * @var int
     *
     * @ORM\Column(name="anyoGraduacion", type="integer", nullable=true)
     */
    private $anyoGraduacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comunicacionEscritaUrl", type="string", length=255, nullable=true)
     */
    private $comunicacionEscritaUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cartaConductaUrl", type="string", length=255, nullable=true)
     */
    private $cartaConductaUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="reporteNotaUrl", type="string", length=255, nullable=true)
     */
    private $reporteNotaUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="promedioAcademico", type="integer", nullable=true)
     */
    private $promedioAcademico;

    /**
     * @var int
     *
     * @ORM\Column(name="notaIntento1", type="integer", nullable=true)
     */
    private $notaIntento1;

    /**
     * @var int
     *
     * @ORM\Column(name="notaIntento2", type="integer", nullable=true)
     */
    private $notaIntento2;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="$fechaRenuncia", type="datetime", nullable=true)
     */
    private $fechaRenuncia;
    
    /**
     * @ORM\ManyToOne(targetEntity="Concurso", inversedBy="aspirantes")
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
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return Aspirante
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     *
     * @return Aspirante
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return Aspirante
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return Aspirante
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     *
     * @return Aspirante
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return int
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Aspirante
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     *
     * @return Aspirante
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set telefonoSecundario
     *
     * @param integer $telefonoSecundario
     *
     * @return Aspirante
     */
    public function setTelefonoSecundario($telefonoSecundario)
    {
        $this->telefonoSecundario = $telefonoSecundario;

        return $this;
    }

    /**
     * Get telefonoSecundario
     *
     * @return int
     */
    public function getTelefonoSecundario()
    {
        return $this->telefonoSecundario;
    }

    /**
     * Set universidadEgresado
     *
     * @param string $universidadEgresado
     *
     * @return Aspirante
     */
    public function setUniversidadEgresado($universidadEgresado)
    {
        $this->universidadEgresado = $universidadEgresado;

        return $this;
    }

    /**
     * Get universidadEgresado
     *
     * @return string
     */
    public function getUniversidadEgresado()
    {
        return $this->universidadEgresado;
    }

    /**
     * Set descripcionTituloUniv
     *
     * @param string $descripcionTituloUniv
     *
     * @return Aspirante
     */
    public function setDescripcionTituloUniv($descripcionTituloUniv)
    {
        $this->descripcionTituloUniv = $descripcionTituloUniv;

        return $this;
    }

    /**
     * Get descripcionTituloUniv
     *
     * @return string
     */
    public function getDescripcionTituloUniv()
    {
        return $this->descripcionTituloUniv;
    }

    /**
     * Set anyoGraduacion
     *
     * @param integer $anyoGraduacion
     *
     * @return Aspirante
     */
    public function setAnyoGraduacion($anyoGraduacion)
    {
        $this->anyoGraduacion = $anyoGraduacion;

        return $this;
    }

    /**
     * Get anyoGraduacion
     *
     * @return int
     */
    public function getAnyoGraduacion()
    {
        return $this->anyoGraduacion;
    }
    
    /**
     * Set comunicacionEscritaUrl
     *
     * @param string $comunicacionEscritaUrl
     *
     * @return Aspirante
     */
    public function setComunicacionEscritaUrl($comunicacionEscritaUrl)
    {
        $this->comunicacionEscritaUrl = $comunicacionEscritaUrl;

        return $this;
    }

    /**
     * Get comunicacionEscritaUrl
     *
     * @return string
     */
    public function getComunicacionEscritaUrl()
    {
        return $this->comunicacionEscritaUrl;
    }

    /**
     * Set cartaConductaUrl
     *
     * @param string $cartaConductaUrl
     *
     * @return Aspirante
     */
    public function setCartaConductaUrl($cartaConductaUrl)
    {
        $this->cartaConductaUrl = $cartaConductaUrl;

        return $this;
    }

    /**
     * Get cartaConductaUrl
     *
     * @return string
     */
    public function getCartaConductaUrl()
    {
        return $this->cartaConductaUrl;
    }

    /**
     * Set reporteNotaUrl
     *
     * @param string $reporteNotaUrl
     *
     * @return Aspirante
     */
    public function setReporteNotaUrl($reporteNotaUrl)
    {
        $this->reporteNotaUrl = $reporteNotaUrl;

        return $this;
    }

    /**
     * Get reporteNotaUrl
     *
     * @return string
     */
    public function getReporteNotaUrl()
    {
        return $this->reporteNotaUrl;
    }

    /**
     * Set promedioAcademico
     *
     * @param integer $promedioAcademico
     *
     * @return Aspirante
     */
    public function setPromedioAcademico($promedioAcademico)
    {
        $this->promedioAcademico = $promedioAcademico;

        return $this;
    }

    /**
     * Get promedioAcademico
     *
     * @return int
     */
    public function getPromedioAcademico()
    {
        return $this->promedioAcademico;
    }

    /**
     * Set notaIntento1
     *
     * @param integer $notaIntento1
     *
     * @return Aspirante
     */
    public function setNotaIntento1($notaIntento1)
    {
        $this->notaIntento1 = $notaIntento1;

        return $this;
    }

    /**
     * Get notaIntento1
     *
     * @return int
     */
    public function getNotaIntento1()
    {
        return $this->notaIntento1;
    }

    /**
     * Set notaIntento2
     *
     * @param integer $notaIntento2
     *
     * @return Aspirante
     */
    public function setNotaIntento2($notaIntento2)
    {
        $this->notaIntento2 = $notaIntento2;

        return $this;
    }

    /**
     * Get notaIntento2
     *
     * @return int
     */
    public function getNotaIntento2()
    {
        return $this->notaIntento2;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Aspirante
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
     * Set fechaRenuncia
     *
     * @param \DateTime $fechaRenuncia
     *
     * @return Concurso
     */
    public function setFechaRenuncia($fechaRenuncia)
    {
        $this->fechaRenuncia = $fechaRenuncia;

        return $this;
    }

    /**
     * Get fechaRenuncia
     *
     * @return \DateTime
     */
    public function getFechaRenuncia()
    {
        return $this->fechaRenuncia;
    }


}
