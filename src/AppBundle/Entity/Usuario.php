<?php		
 		
 namespace AppBundle\Entity;		
 		
 use Doctrine\ORM\Mapping as ORM;		
 use Doctrine\Common\Collections\ArrayCollection;		
 use Symfony\Component\Security\Core\User\UserInterface;		
 use AppBundle\Entity\Rol;		
 		
 use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;		
 use RegistroUnicoBundle\Entity\Departamento;		
 		
 /**		
  * @ORM\Entity		
  * @ORM\Entity(repositoryClass="AppBundle\Repository\UsuarioRepository")		
  */		
 class Usuario implements UserInterface		
 {		
     /**		
      * @ORM\Id		
      * @ORM\Column(type="integer")		
      * @ORM\GeneratedValue(strategy="AUTO")		
      */		
     private $id;		
 		
     /**		
      *  @ORM\Column(type="integer")		
      */		
     //private $estatusId;		
 		
     /**		
      * @ORM\Column(type="string", length=25)		
      */		
     private $cedula;		
 		
     /**		
      * @ORM\Column(type="string", length=100)		
      */		
     private $direccion;		
 		
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
      * @ORM\Column(type="string")		
      */		
     private $nacionalidad;		
 		
     /**		
      * @ORM\Column(type="string", length=50)		
      */		
     private $correo;		
 		
     /**		
      * @ORM\Column(type="integer")		
      */		
     private $edad;		
     		
     /**		
      * @ORM\Column(type="string")		
      */		
     private $telefono;		
 		
     /**		
      * @ORM\Column(type="string")		
      */		
     private $sexo;		
     		
     /**		
      * @ORM\Column(type="string")		
      */		
     private $rif;		
 		
     /**		
       * @ORM\Column(type="datetime", nullable=true)		
       *		
       */		
     private $fechaNacimiento;		
 		
     /**		
       * @ORM\Column(type="datetime", nullable=true)		
       */		
     private $fechaFallecimiento;		
 		
     /**		
      * @ORM\Column(name="contrasena", type="string", length=100)		
      */		
     private $contraseña;		
 		
     /**		
      * @ORM\Column(type="boolean")		
      */		
     private $activo;		
 		
     /**		
      * @ORM\ManyToMany(targetEntity="Rol")		
      * @ORM\JoinTable(name="usuario_rol",		
      *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},		
      *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id")}		
      *      )		
      */		
     protected $roles;		
 		
     /**		
      * @ORM\ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Registro")		
      * @ORM\JoinTable(name="usuarios_registros",		
      *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},		
      *      inverseJoinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id")}		
      *      )		
      */		
     protected $registros;		
 		
     /**		
      * @ORM\ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Cargo")		
      * @ORM\JoinTable(name="usuarios_cargos",		
      *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},		
      *      inverseJoinColumns={@ORM\JoinColumn(name="cargo_id", referencedColumnName="id")}		
      *      )		
      */		
     protected $cargos;		
 		
     /**		
      * @ORM\ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Facultad")		
      * @ORM\JoinTable(name="usuarios_facultades",		
      *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},		
      *      inverseJoinColumns={@ORM\JoinColumn(name="facultad_id", referencedColumnName="id")}		
      *      )		
      */		
     protected $facultades;		
 		
     /**		
      * @ORM\ManyToMany(targetEntity="ClausulasContractualesBundle\Entity\Hijo")		
      * @ORM\JoinTable(name="usuario_hijo",		
      *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},		
      *      inverseJoinColumns={@ORM\JoinColumn(name="hijo_id", referencedColumnName="id")}		
      *      )		
      */		
     protected $hijos;		
 		
     /**		
      * @ORM\OneToMany(targetEntity="PlanSeptenalBundle\Entity\PlanSeptenalIndividual", mappedBy="owner")		
      */		
     protected $planes_septenales_individuales;		
 		
     /**		
      * @ORM\OneToOne(targetEntity="RegistroUnicoBundle\Entity\Departamento")		
      * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")		
      */		
     /*private $departamento;*/		
 		
     public function __construct()		
     {		
         $this->roles = new ArrayCollection();
         $this->cargos = new ArrayCollection();
         $this->planes_septenales_individuales = new ArrayCollection();		
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
       * Set edad		
       *		
       * @param integer $edad		
       *		
       * @return Usuario		
       */		
      public function setEdad($edad)		
      {		
         $this->edad = $edad;		
 		
         return $this;		
     }		
 		
     /**		
      * Get edad		
      *		
      * @return integer		
      */		
     public function getEdad()		
     {		
         return $this->edad;		
     }		
     		
      /**		
      * Set sexo		
      *		
      * @param string $sexo		
      *		
      * @return Usuario		
      */		
     public function setSexo($sexo)		
     {		
         $this->sexo = $sexo;		
 		
         return $this;		
     }		
 		
     /**		
      * Get sexo		
      *		
      * @return string		
      */		
     public function getSexo()		
     {		
         return $this->sexo;		
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
      * @param string $telefono		
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
      * @return string		
      */		
     public function getTelefono()		
     {		
         return $this->telefono;		
     }		
 		
     /**		
      * Set rif		
      *		
      * @param string $rif		
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
      * @return string		
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
     public function setPassword($contraseña)		
     {		
         $this->contraseña = $contraseña;		
 		
         return $this;		
     }		
 		
     /**		
      * Get contraseña		
      *		
      * @return string		
      */		
     public function getPassword()		
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
 		
     /**		
      * Set direccion		
      *		
      * @param string $direccion		
      *		
      * @return Usuario		
      */		
     public function setDireccion($direccion)		
     {		
         $this->direccion = $direccion;		
 		
         return $this;		
     }		
 		
     /**		
      * Get direccion		
      *		
      * @return string		
      */		
     public function getDireccion()		
     {		
         return $this->direccion;		
     }		
 		
     public function getCargos()
     {
        // profiler needs at least one rol to consider the user logged in
        $cargos = array_reduce($this->cargos->toArray(), function ($cargo_names, $cargo) {
            $cargo_names[] = $cargo->getDescription();
            return $cargo_names;
        }, []);

        return $cargos;
     }
    
     public function addCargo($cargo)
     {
        $this->cargos[] = $cargo;
     }

     public function addCargos($cargos)
     {
        foreach($cargos as $cargo)
            $this->addCargo($cargo);
     } 
     
     public function getRoles()		
     {		
         // profiler needs at least one rol to consider the user logged in		
         $roles = array_reduce($this->roles->toArray(), function ($rol_names, $rol) {		
             $rol_names[] = $rol->getNombre();		
             return $rol_names;		
         }, []);		
 		
         return $roles;		
     }		
 		
     public function addRol($rol)		
     {		
         $this->roles[] = $rol;		
     }		
 		
     public function addRoles($roles)		
     {		
         foreach($roles as $rol)		
             $this->addRol($rol);		
     }		
 		
     public function getUsername()		
     {		
         // shows username in profiler		
         return $this->getNombreCorto();		
     }		
 		
     public function getSalt(){}		
 		
     public function eraseCredentials(){}		
 		
     public function getNombreCorto()		
     {		
         return $this->getPrimerNombre().' '.$this->getPrimerApellido();		
     }		
 		
     public function getNombreCompleto()		
     {		
         return $this->getPrimerNombre().' '.$this->getSegundoNombre().' '.$this->getPrimerApellido().' '.$this->getSegundoApellido();		
     }		
 		
     public function ownPlanSeptenalIndividual(PlanSeptenalIndividual $plan)		
     {		
         $this->planes_septenales_individuales[] = $plan;		
         return $this;		
     }		
 		
     public function getPlanesSeptenalesIndividuales()		
     {		
         return $this->planes_septenales_individuales;		
     }		
     		
     /*public function setDepartamento(Departamento $departamento)		
     {		
         $this->departamento = $departamento;		
         return $this;		
     }		
     public function getDepartamento()		
     {		
         return $this->departamento;		
     }*/		
 }

