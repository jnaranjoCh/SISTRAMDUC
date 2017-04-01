<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Registro")
 */
class Registro
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $año;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $institucionEmpresa;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoRegistro", inversedBy="registros")
     * @ORM\JoinColumn(name="tipo_registro_id", referencedColumnName="id")
     */
    protected $tipo_registro;
    
    /**
     * @ORM\ManyToOne(targetEntity="Nivel", inversedBy="registros")
     * @ORM\JoinColumn(name="nivel_id", referencedColumnName="id")
     */
    protected $nivel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estatus", inversedBy="registros")
     * @ORM\JoinColumn(name="estatus_id", referencedColumnName="id")
     */
    protected $estatus;
    

    /**
     * @ORM\ManyToMany(targetEntity="Participante")
     * @ORM\JoinTable(name="registro_participantes",
     *      joinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="participante_id", referencedColumnName="id")}
     *      )
     */
    protected $participantes;

    /**
     * @ORM\ManyToMany(targetEntity="Revista")
     * @ORM\JoinTable(name="registro_revistas",
     *      joinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="revista_id", referencedColumnName="id")}
     *      )
     */
    protected $revistas;
    
    
    protected $usuarios;
    /**
     * @ORM\Column(type="integer")
     */
    private $tipoId;

    /**
     * @ORM\Column(type="integer")
     */
    private $nivelId;

    /**
     * @ORM\Column(type="integer")
     */
    private $estatusId;


    public function __construct()
    {
        $this->participantes = new ArrayCollection();
        $this->revistas = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set año
     *
     * @param integer $año
     *
     * @return Registro
     */
    public function setAño($año)
    {
        $this->año = $año;

        return $this;
    }

    /**
     * Get año
     *
     * @return integer
     */
    public function getAño()
    {
        return $this->año;
    }

    /**
     * Set $institucionEmpresa
     *
     * @param string $institucionEmpresa
     *
     * @return Registro
     */
    public function setInstitucionEmpresa($institucionEmpresa)
    {
        $this->institucionEmpresa = $institucionEmpresa;

        return $this;
    }

    /**
     * Get $institucionEmpresa
     *
     * @return string
     */
    public function getInstitucionEmpresa()
    {
        return $this->institucionEmpresa;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Registro
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set tipoId
     *
     * @param integer $tipoId
     *
     * @return Registro
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return integer
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Set nivelId
     *
     * @param integer $nivelId
     *
     * @return Registro
     */
    public function setNivelId($nivelId)
    {
        $this->nivelId = $nivelId;

        return $this;
    }

    /**
     * Get nivelId
     *
     * @return integer
     */
    public function getNivelId()
    {
        return $this->nivelId;
    }

    /**
     * Set estatusId
     *
     * @param integer $estatusId
     *
     * @return Registro
     */
    public function setEstatusId($estatusId)
    {
        $this->estatusId = $estatusId;

        return $this;
    }

    /**
     * Get estatusId
     *
     * @return integer
     */
    public function getEstatusId()
    {
        return $this->estatusId;
    }
    
    
    /**
     * Get tipo_registro
     *
     * @return TipoRegistro
     */
    public function getTipo()
    {
        return $this->tipo_registro;
    }

    /**
     * Set tipo_registro
     *
     * @param TipoRegistro $tipo_registro
     *
     * @return Registro
     */
    public function setTipo(TipoRegistro $tipo_registro)
    {
        $this->tipo_registro = $tipo_registro;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return Nivel
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set nivel
     *
     * @param Nivel $nivel
     *
     * @return Registro
     */
    public function setNivel(Nivel $nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }
    
    /**
     * Get estatus
     *
     * @return integer
     */
    public function getEstatus()
    {
        return $this->estatus;
    }
    
    /**
     * Set estatus
     *
     * @param Estatus $estatus
     *
     * @return Registro
     */
    public function setEstatus(Estatus $estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }
    
}
