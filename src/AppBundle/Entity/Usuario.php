<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Usuario")
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $cedula;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $primerNombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $segundoNombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $correo;

    /** 
     * @ORM\@Column(type="integer") 
     */
    private $telefono;

    /** 
     * @ORM\@Column(type="integer") 
     */
    private $rif;

    /** 
      * @ORM\@Column(type="datetime") 
      */
    private $fechaNacimiento;

    /** 
      * @ORM\@Column(type="datetime") 
      */
    private $fechaFallecimiento;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $contraseña;

    /**
     * @ORM\Column(type="bool")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="usuarios")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    protected $rol;
    
    /**
     * @ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Registro", inversedBy="usuarios")
     * @JoinTable(name="usuarios_registros",
     *      joinColumns={@JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="registro_id", referencedColumnName="id")}
     *      )
     */
    protected $registros;
    
    /**
     * @ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Cargo", inversedBy="usuarios")
     * @JoinTable(name="usuarios_cargos",
     *      joinColumns={@JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="cargo_id", referencedColumnName="id")}
     *      )
     */
    protected $cargos;
    
    /**
     * @ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Facultad", inversedBy="usuarios")
     * @JoinTable(name="usuarios_facultades",
     *      joinColumns={@JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="facultad_id", referencedColumnName="id")}
     *      )
     */
    protected $facultades;
    
    /**
     *  @ORM\@Column(type="integer")
     */
    private $rolId;
    
    public function __construct()
    {
        $this->registros = new ArrayCollection();
        $this->cargos = new ArrayCollection();
        $this->facultades = new ArrayCollection();
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
     * Set cedula
     *
     * @param string $cedula
     *
     * @return Usuario
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
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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
     * Set nacionalidad
     *
     * @param string $nacionalidad
     *
     * @return Usuario
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Usuario
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
     * Set telefono
     *
     * @param integer $telefono
     *
     * @return Usuario
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
     * Set rif
     *
     * @param integer $rif
     *
     * @return Usuario
     */
    public function setRif($rif)
    {
        $this->rif = $rif;

        return $this;
    }

    /**
     * Get rif
     *
     * @return int
     */
    public function getRif()
    {
        return $this->rif;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Usuario
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set fechaFallecimiento
     *
     * @param \DateTime $fechaFallecimiento
     *
     * @return Usuario
     */
    public function setFechaFallecimiento($fechaFallecimiento)
    {
        $this->fechaFallecimiento = $fechaFallecimiento;

        return $this;
    }

    /**
     * Get fechaFallecimiento
     *
     * @return \DateTime
     */
    public function getFechaFallecimiento()
    {
        return $this->fechaFallecimiento;
    }

    /**
     * Set contraseña
     *
     * @param string $contraseña
     *
     * @return Usuario
     */
    public function setContraseña($contraseña)
    {
        $this->contraseña = $contraseña;

        return $this;
    }

    /**
     * Get contraseña
     *
     * @return string
     */
    public function getContraseña()
    {
        return $this->contraseña;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }
}

