<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Rol;
use RegistroUnicoBundle\Entity\UsuarioFechaCargo;
use ClausulasContractualesBundle\Entity\Hijo;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use RegistroUnicoBundle\Entity\Departamento;
use RegistroUnicoBundle\Entity\Cargo;
use TramiteBundle\Entity\Tramite;
use DescargaHorariaBundle\Entity\NombramientoCargoAdmUniv;
use \stdClass;

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
     * @ORM\Column(type="boolean")
     */
    private $isRegister;

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
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $roles;

    /**
     * @ORM\ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Registro", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="usuarios_registros",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $registros;


    /**
     * @ORM\OneToMany(targetEntity="RegistroUnicoBundle\Entity\UsuarioFechaCargo", mappedBy="usuarios", cascade={"persist", "remove"})
     */
    protected $UsuarioFechaCargos;

    /**
     * @ORM\ManyToMany(targetEntity="RegistroUnicoBundle\Entity\Facultad")
     * @ORM\JoinTable(name="usuarios_facultades",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="facultad_id", referencedColumnName="id")}
     *      )
     */
    protected $facultades;

    /**
     * @ORM\ManyToMany(targetEntity="ClausulasContractualesBundle\Entity\Hijo", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="usuario_hijo",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="hijo_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $hijos;

    /**
     * @ORM\OneToMany(targetEntity="PlanSeptenalBundle\Entity\PlanSeptenalIndividual", mappedBy="owner")
     */
    protected $planes_septenales_individuales;

    /**
     * @ORM\OneToMany(targetEntity="TramiteBundle\Entity\Recaudo", mappedBy="usuario", cascade={"remove", "persist"})
     */
    protected $recaudos;

    /**
     * @ORM\ManyToOne(targetEntity="RegistroUnicoBundle\Entity\Departamento")
     */
    private $departamento;

    /**
     * @ORM\OneToMany(targetEntity="TramiteBundle\Entity\Tramite", mappedBy="usuario_id", cascade={"persist"})
     */
    protected $tramite;
    
    /**
     * @ORM\OneToOne(targetEntity="DescargaHorariaBundle\Entity\TipoDedicacion")
     * @ORM\JoinColumn(name="tipo_dedicacion_id", referencedColumnName="id")
     */ 
    protected $tipo_dedicacion_id;
    
    /**
     * @ORM\OneToMany(targetEntity="DescargaHorariaBundle\Entity\NombramientoCargoAdmUniv", mappedBy="usuario_id", cascade={"persist"})
     */
    protected $nombramiento_cargo;
    

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->UsuarioFechaCargos = new ArrayCollection();
        $this->planes_septenales_individuales = new ArrayCollection();
        $this->tramite = new ArrayCollection();
        $this->hijos = new ArrayCollection();
        $this->nombramiento_cargo = new ArrayCollection();
    }

    public function getUsuarioFechaCargos()
    {
        return $this->UsuarioFechaCargos->toArray();
    }
    
    public function setUsuarioFechaCargos($UsuarioFechaCargos)
    {
        $this->UsuarioFechaCargos = $UsuarioFechaCargos;
        foreach($UsuarioFechaCargos as $object)
        {
          $object->setUsuario($this);
        }
        return $this;
    }
    
    public function addUsuarioFechaCargos(UsuarioFechaCargo $UsuarioFechaCargos)
    {
        if (!$this->UsuarioFechaCargos->contains($UsuarioFechaCargos)) {
            $this->UsuarioFechaCargos->add($UsuarioFechaCargos);
            $UsuarioFechaCargos->setUsuario($this);
        }

        return $this;
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
     * Set activo
     *
     * @param boolean $isRegister
     *
     * @return Usuario
     */
    public function setIsRegister($isRegister)
    {
        $this->isRegister = $isRegister;

        return $this;
    }

    /**
     * Get isRegister
     *
     * @return bool
     */
    public function getIsRegister()
    {
        return $this->isRegister;
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

	public function getRegistros($assets)
    {
        $registros = new stdClass;

        $i = 0;
        $data[] = [];
        foreach($this->registros->toArray() as $registro){
            if($assets == "assets")
                $data[$i]['Delete'] = "<img src='/assets/images/delete.png' width='30px' heigth='30px'/>";
            else
                $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
            $data[$i]['Id'] = $registro->getId();
            $data[$i]['TipoDeReferencia'] = $registro->getTipo()->getDescription();
            $data[$i]['Descripcion'] = '<input id="Descripcion'.$i.'" value="'.$registro->getDescription().'" type="text" class="form-control" placeholder="Descripción">';
            $data[$i]['Nivel'] = $registro->getNivel()->getDescription();
            $data[$i]['Estatus'] = $registro->getEstatus()->getDescription();
            $data[$i]['AnoDePublicacionAsistencia'] = '<input id="AnoDePublicacionAsistencia'.$i.'" value="'.$registro->getAño().'" type="number" class="form-control" placeholder="Año de publicación y/o asistencia">';
            if($registro->getInstitucionEmpresaCasaeditorial() == "")
                $data[$i]['EmpresaInstitucion'] = '<input id="EmpresaInstitucion'.$i.'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">';
            else
                $data[$i]['EmpresaInstitucion'] = '<input id="EmpresaInstitucion'.$i.'" value="'.$registro->getInstitucionEmpresaCasaeditorial().'" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">';
            if($registro->getTituloObtenido() == "")
                $data[$i]['TituloObtenido'] = '<input id="TituloObtenido'.$i.'" value="" type="text" class="form-control" placeholder="Titulo Obtenido" readonly>';
            else
                $data[$i]['TituloObtenido'] = '<input id="TituloObtenido'.$i.'" value="'.$registro->getTituloObtenido().'" type="text" class="form-control" placeholder="Titulo Obtenido">';
            if($registro->getCiudadPais() == "")
                $data[$i]['CiudadPais'] = '<input id="CiudadPais'.$i.'" value="" type="text" class="form-control" placeholder="Ciudad / Pais" readonly>';
            else
                $data[$i]['CiudadPais'] = '<input id="CiudadPais'.$i.'" value="'.$registro->getCiudadPais().'" type="text" class="form-control" placeholder="Ciudad / Pais">';
            if($registro->getCongreso() == "")
                $data[$i]['Congreso'] = '<input id="Congreso'.$i.'" value="" type="text" class="form-control" placeholder="Congreso" readonly>';
            else
                $data[$i]['Congreso'] = '<input id="Congreso'.$i.'" value="'.$registro->getCongreso().'" type="text" class="form-control" placeholder="Congreso">';
            $i++;
        }


        $registros->data = $data;
        $registros->num = $i;

        return $registros;
    }

    public function getRegistrosValidate()
    {
        $registros = new stdClass;

        $i = 0;
        $data[] = [];
        foreach($this->registros->toArray() as $registro){
            $data[$i]['Id del registro'] = $registro->getId();
            $data[$i]['Tipo de referencia'] = $registro->getTipo()->getDescription();
            $data[$i]['Descripcion'] = $registro->getDescription();
            $data[$i]['Nivel'] = $registro->getNivel()->getDescription();
            $data[$i]['Estatus'] = $registro->getEstatus()->getDescription();
            $data[$i]['Año de publicación y/o asistencia'] = $registro->getAño();
            if($registro->getInstitucionEmpresaCasaeditorial() == null)
                $data[$i]['Empresa y/o institución'] = "";
            else
                $data[$i]['Empresa y/o institución'] = $registro->getInstitucionEmpresaCasaeditorial();
            $data[$i]['Titulo Obtenido'] = $registro->getTituloObtenido();
            $data[$i]['CiudadPais'] = $registro->getCiudadPais();
            $data[$i]['Congreso'] = $registro->getCiudadPais();
            if($registro->getIsValidate())
                $data[$i]['Validado'] = '<div class="row"><div class="col-xs-6"><span id="span'.$i.'" class="label label-success">Validado</span></div> <div class="col-xs-4 col-xs-offset-2"><input type="checkbox" id="checkboxValidarRegistro'.$i.'" name="checkboxValidarRegistro'.$i.'" value="validado" checked/></div></div>';
            else
                $data[$i]['Validado'] = '<div class="row"><div class="col-xs-6"><span id="span'.$i.'" class="label label-warning">No validado</span></div> <div class="col-xs-4 col-xs-offset-2"><input type="checkbox" id="checkboxValidarRegistro'.$i.'" name="checkboxValidarRegistro'.$i.'" value="validado"/></div></div>';
            $i++;
        }

        $registros->data = $data;
        $registros->num = $i;

        return $registros;
    }
    
	public function getRegistrosParticipantes($assets)
    {
        $registros = new stdClass;

        $i = 0;
        $k = 0;
        $data[] = [];
        $aux[] = [];

        $htmlIdRegistros = '<select id="IdDelRegistro'.$i.'" class="form-control select2" style="width: 240px;">';
        foreach($this->registros->toArray() as $registro){
            $htmlIdRegistros = $htmlIdRegistros."<option value='".$registro->getId()."'>".$registro->getId()."</option>";
        }
        $htmlIdRegistros = $htmlIdRegistros."</select>";

        foreach($this->registros->toArray() as $registro){
            $htmlIdRegistrosAux = str_replace("<option value='".$registro->getId()."'>".$registro->getId()."</option>","<option value='".$registro->getId()."' selected='selected'>".$registro->getId()."</option>",$htmlIdRegistros);
            $aux = $registro->getParticipantes($htmlIdRegistrosAux,$k);
            for($j = 0; $j < $aux->num; $j++)
            {
                if($assets == "assets")
                    $data[$i]['Delete'] = "<img src='/assets/images/delete.png' width='30px' heigth='30px'/>";
                else
                    $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['IdDelRegistro'] = $aux->data[$j]['IdDelRegistro'];
                $data[$i]['Nombre'] = $aux->data[$j]['Nombre'];
                $data[$i]['Cedula'] = $aux->data[$j]['Cedula'];
                $i++;
            }
        }

        $registros->data = $data;
        $registros->num = $i;
        return $registros;
    }

    public function getRegistrosRevistas($assets)
    {
        $registros = new stdClass;

        $i = 0;
        $k = 0;
        $data[] = [];
        $aux[] = [];

        $htmlIdRegistros = '<select id="IdDelRegistroRevista'.$i.'" class="form-control select2" style="width: 240px;">';
        foreach($this->registros->toArray() as $registro){
            $htmlIdRegistros = $htmlIdRegistros."<option value='".$registro->getId()."'>".$registro->getId()."</option>";
        }
        $htmlIdRegistros = $htmlIdRegistros."</select>";

        foreach($this->registros->toArray() as $registro){
            $htmlIdRegistrosAux = str_replace("<option value='".$registro->getId()."'>".$registro->getId()."</option>","<option value='".$registro->getId()."' selected='selected'>".$registro->getId()."</option>",$htmlIdRegistros);
            $aux = $registro->getRevistas($htmlIdRegistrosAux,$k);
            for($j = 0; $j < $aux->num; $j++)
            {
                if($assets == "assets")
                    $data[$i]['Delete'] = "<img src='/assets/images/delete.png' width='30px' heigth='30px'/>";
                else
                    $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['IdDelRegistro'] = $aux->data[$j]['IdDelRegistro'];
                $data[$i]['Revista'] = $aux->data[$j]['Revista'];
                $data[$i]['Volumen'] = $aux->data[$j]['Volumen'];
                $data[$i]['PrimerayUltimaPagina'] = $aux->data[$j]['PrimerayUltimaPagina'];
                $i++;
            }
        }

        $registros->data = $data;
        $registros->num = $i;
        return $registros;
    }

    public function addRegistro($registro)
    {
       $this->registros[] = $registro;
    }

    public function addRegistros($registros)
    {
       foreach($registros as $registro)
           $this->addRegistro($registro);
    }

    public function getHijos()
    {
       // profiler needs at least one rol to consider the user logged in
       $hijos = array_reduce($this->hijos->toArray(), function ($hijo_names, $hijo) {
           $hijo_names[] = $hijo->getNombreCompleto();
           return $hijo_names;
       }, []);

       return $hijos;
    }

    public function getHijosObject()
    {
       return $this->hijos;
    }
    
    public function addHijo($hijo)
    {
       $this->hijos[] = $hijo;
    }

    public function addHijos($hijos)
    {
       foreach($hijos as $hijo)
           $this->addHijo($hijo);
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

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }
    
    public function setRegistros($registros)
    {
        $this->registros = $registros;
        return $this;
    }

    public function getRolesAsObjects()
    {
        return $this->roles;
    }
    
    public function getHijosAsObjects()
    {
        return $this->hijos;
    }

    public function addRol($rol)
    {
        $this->roles[] = $rol;
    }

    public function getRegistrosAsObject()
    {
        return $this->registros;
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
        return $this->joinNames([$this->primerNombre, $this->primerApellido]);
    }

    public function getNombreCompleto()
    {
        return $this->joinNames([$this->primerNombre, $this->segundoNombre, $this->primerApellido, $this->segundoApellido]);
    }

    private function joinNames(array $names)
    {
        $joined_names = "";
        foreach ($names as $name) {
            if (! empty($name)) {
                $joined_names .= (empty($joined_names) ? "" : " ") . $name;
            }
        }

        return $joined_names;
    }

    public function setNombreCompleto(string $primerNombre = null, string $segundoNombre = null, string $primerApellido = null, string $segundoApellido = null)
    {
        $this->primerNombre = is_null($primerNombre) ? "" : $primerNombre;
        $this->segundoNombre = is_null($segundoNombre) ? "" : $segundoNombre;
        $this->primerApellido = is_null($primerApellido) ? "" : $primerApellido;
        $this->segundoApellido = is_null($segundoApellido) ? "" : $segundoApellido;

        return $this;
    }

    public function getPlanesSeptenalesIndividuales()
    {
        return $this->planes_septenales_individuales;
    }

    public function setDepartamento(Departamento $departamento)
    {
        $this->departamento = $departamento;
        return $this;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    public function ownTramite(Tramite $tramite)
    {
        $this->tramite[] = $tramite;
        return $this;
    }

    public function getTramite()
    {
        return $this->tramite;
    }

    public function __toString() {
        return sprintf($this->getId().' ('.$this->getPrimerNombre().')'.' ('.$this->getPrimerApellido());
    }
    
    public function setHijos($hijos)
    {
        $this->hijos = $hijos;
        return $this;
    }
   
    public function getNombramientoCargo()
    {
       return $this->nombramiento_cargo;
    }
    
}
